<?php

namespace app\controllers;

use app\models\BSEpisodes;
use app\models\BSUrl;
use Yii;
use yii\web\Controller;

class BsController extends Controller
{
  /**
   * @var string
   */
  private $downloadFolder = 'data';
  /**
   * @var string
   */
  private $linkTitle = 'OpenLoadHD';
  /**
   * @var DOMDocument
   */
  private $BSDom;
  /**
   * @var array
   */
  private $skipedEpisodes = [];
  /**
   * @var array
   */
  private $session = [];
  /**
   * @var array
   */
  private $serie = [];
  /**
   * @var string
   */
  private $serieTitle;

  private $progressPercentageStep = 1;
  private $downloadPercentage = 0;

  public function beforeAction($action)
  {
    parent::beforeAction($action);
    $this->session = Yii::$app->session;
    if (!$this->session->isActive) {
      $this->session->open();
    }
    if (isset($this->session['serie'])) $this->serie = $this->session['serie'];
    if (isset($this->serie['title'])) $this->serieTitle = $this->serie['title'];
    return true;
  }

  public function afterAction($action, $result)
  {
    $this->session['serie'] = $this->serie;
    if (is_array($result)) {
      die(json_encode($result));
    }
    return parent::afterAction($action, $result);
  }

  /**
   * Displays a simple Form
   *
   * @return string
   */
  public function actionIndex()
  {
    $model = new BSUrl();
    if ($model->load(Yii::$app->request->post()) && $model->validate()) {
      //url is valid. Start parsing content
      $this->setBSDom($model->seriesUrl);
      $this->serie = [
        'title' => $this->parseTitle(),
        'seasons' => $this->parseSeasons()
      ];
      return $this->serie;
    } else {
      //initially page load or invalid url
      return $this->render('index', ['model' => $model]);
    }
  }

  private function setBSDom($bsUrl)
  {
    $bsSite = file_get_contents($bsUrl);
    //load DOM
    $doc = new \DOMDocument();
    libxml_use_internal_errors(true);
    $doc->loadHTML($bsSite);
    libxml_clear_errors();
    $this->BSDom = $doc;
  }

  private function parseTitle()
  {
    return 'Demo';
  }

  private function parseSeasons()
  {
    $node = $this->BSDom->getElementById('seasons');
    $seasons = [];
    foreach ($node->getElementsByTagName('a') as $key => $link) {
      $seasons[$key]['href'] = $link->getAttribute('href');
      $seasons[$key]['index'] = $key;
//      $seasons[$key]['title'] = 'demo';
    }
    return $this->serie = $seasons;
  }

  public function actionIframe()
  {
    $html = file_get_contents(urldecode($_GET['ep-url']));
    //get original js
    $html = str_replace('src="/', 'src="https://openload.co/', $html);
    die($html);
  }

  public function actionParseEpisodes()
  {
    $model = new BSEpisodes();
    if ($model->load(Yii::$app->request->post()) && $model->validate()) {
      return $this->parseEpisodes($model->seasonIndex);
    } else {
      return ['fail' => 'data could not validate'];
    }
  }

  private function parseEpisodes($seasonIndex)
  {
    $season = &$this->serie['seasons'][$seasonIndex];
    $url = $this->getAbsUrl($season['href']);
    $this->setBSDom($url);
    $episodes = $this->getEpisodes($seasonIndex);
    foreach ($episodes as &$episode) {
      $episode['href'] = $this->getBSLinkToHoster($episode['href']);
    }
    return $season['episodes'] = $episodes;
  }

  private function getAbsUrl($url)
  {
    return 'https://bs.to/' . $url;
  }

  private function getEpisodes($seasonIndex)
  {
    $table = $this->getBSSeriesTable();
    $rows = $table->getElementsByTagName('tr');
    $episodes = [];

    foreach ($rows as $key => $row) {
      $episode = array('href' => '', 'title' => 'demo');
      $episode['season'] = $seasonIndex;
      $episode['index'] = $key;
      $link = $this->getLinkFromBSTableRow($row);
      if (!is_null($link)) {
        $episode['href'] = $link;
        if (file_exists($this->getFileName($seasonIndex, $key))) {
          $episode['saved'] = 'cache';
        } else {
          $episode['saved'] = 'not-available';
        }
      } else {
        $this->skipedEpisodes[$seasonIndex][$key] = $episode;
      }
      $episodes[$key] = $episode;
    }
    return $episodes;
  }

  /**
   * @return DOMNode
   */
  private function getBSSeriesTable()
  {
    $xpath = new \DOMXPath($this->BSDom);
    $table = $xpath->query('//*[contains(@class, \'episodes\')]');
    return $table->item(0);
  }

  /**
   * @param DOMElement $row
   * @return string|null
   */
  private function getLinkFromBSTableRow(\DOMElement $row)
  {
    $tdList = $row->getElementsByTagName('td');
    $td = $tdList->item($tdList->length - 1);
    $linkList = $td->getElementsByTagName('a');

    foreach ($linkList as $link) {
      if ($link->nodeValue == $this->linkTitle) {
        return $link->getAttribute('href');
      }
    }
    return null;
  }

  private function getFileName($seasonIndex, $episodeIndex)
  {
    return $this->downloadFolder . '/' . $this->serieTitle . '/season-' . $seasonIndex . '/episode-' . $episodeIndex . '.mp4';
  }

  public function actionDownload()
  {
    $model = new BSUrl();
    if ($model->load(Yii::$app->request->post()) && $model->validate()) {
      $file = $model->seriesUrl; //in this case file url
      $seasonIndex = $model->seasonIndex;
      $episodeIndex = $model->episodeIndex;
      $season = &$this->serie['seasons'][$seasonIndex];
      $episode = &$season['episodes'][$episodeIndex];
      $episode['file'] = $file;

      $filename = $this->getFileName($seasonIndex, $episodeIndex);
      $path = dirname($filename);

      if (!is_dir($path)) {
        umask(0);
        mkdir($path, 0771, true);
      }

      if (file_exists($filename)) {
        $episode['saved'] = 'cache';
      } elseif ($this->curlDownload($filename, $file)) {
        $episode['saved'] = 'download';
      } else {
        $episode['saved'] = 'failure';
      }

      $response = [
        'saved' => $episode['saved'],
        'season' => 'processing',
        'serie' => 'processing'
      ];
      if (array_slice($season['episodes'], -1) == $episode) {
        $response['season'] = 'finish';
        if (array_slice($this->serie['seasons'] == $season)) {
          $response['serie'] = 'finish';
        }
      }
      return $response;
    }
  }

  /**
   * @param $episodeLink
   * @return mixed
   */
  private function getBSLinkToHoster($episodeLink)
  {
    $bsHTML = file_get_contents($this->getAbsUrl($episodeLink));
    $doc = new \DOMDocument();
    $doc->loadHTML($bsHTML);

    $xpath = new \DOMXPath($doc);
    $linkList = $xpath->query('//*[contains(@class, \'hoster-player\')]');
    $link = $linkList->item(0);
    return $link->getAttribute('href');
  }

  private function actionDownloadZip()
  {
    $files = $this->getUncachhedFiles();
    if (empty($files)) {
      foreach ($this->serie['seasons'] as $seasonIndex => $season) {
        foreach ($season['episodes'] as $episodeIndex => $episode) {

        }
      }
    } else {
      return $files;
    }
  }

  private function getUncachhedFiles()
  {
    $uncachedFiles = [];
    foreach ($this->serie['seasons'] as $seasonIndex => $season) {
      foreach ($season['episodes'] as $episodeIndex => $episode) {
        if (!file_exists($this->getFileName($seasonIndex, $episodeIndex))) {
          $uncachedFiles[] = [
            'season' => $seasonIndex,
            'episode' => $seasonIndex
          ];
        }
      }
    }
    return $uncachedFiles;
  }


  public function actionEindex()
  {
    $serie = [
      'title' => 'Test Serie',
      'seasons' => [
        [
          'href' => 'sample.de',
          'title' => 'Seasons Title 1'
        ],
        [
          'href' => 'sample.de',
          'title' => 'Seasons Title 2',
        ],
        [
          'href' => 'sample.de',
          'title' => 'Seasons Title 3'
        ]
      ]
    ];
    die(json_encode($serie));
  }

  public function actionEparseEpisodes()
  {
    $episode = [
      [
        'href' => 'sample.de',
        'title' => 'Episode 1',
        'saved' => 'not-available'
      ],
      [
        'href' => 'sample.de',
        'title' => 'Episode 2',
        'saved' => 'cache'
      ],
      [
        'href' => 'sample.de',
        'title' => 'Episode 3',
        'saved' => 'not-available'
      ],
      [
        'href' => 'sample.de',
        'title' => 'Episode 4',
        'saved' => 'cache'
      ],
    ];
    sleep(3);
    die(json_encode($episode));
  }

  public function actionEDownload()
  {
    $seasonIndex = $_POST['si'];
    $episodeIndex = $_POST['ei'];
    $episode = [];
    $response = [
      'season' => $seasonIndex,
      'episode' => $episode,
      'episodeIndex' => $episodeIndex
    ];
  }

  private function curlDownload($filename, $seriesLink)
  {
    set_time_limit(0);

    $fileStream = fopen($filename, 'w+');

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $seriesLink);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    curl_setopt($ch, CURLOPT_FILE, $fileStream);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_PROGRESSFUNCTION, '\app\controllers\BsController::_progress');
    curl_setopt($ch, CURLOPT_NOPROGRESS, false); // needed to make progress function work
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
    curl_exec($ch);
    $info = curl_getinfo($ch);
    curl_close($ch);
    // TODO: check if curl was successful
    fclose($fileStream);
    return true;
  }

  public function _progress($resource, $download_size, $downloaded, $upload_size, $uploaded)
  {
    if ($download_size > 0) {
      $percentage = round($downloaded / $download_size * 100);
      if ($this->downloadPercentage !== $percentage && $percentage % $this->progressPercentageStep == 0) {
        $this->downloadPercentage = $percentage;
        $status = ['progress' => $percentage];
        echo json_encode($status);
        ob_flush();
        flush();
      }
    }
  }

  public function actionTest()
  {
    set_time_limit(0);
//    die(phpinfo());
    // Get real path for our folder
    $rootPath = realpath('data');
    $zipName = 'archive.zip';

    // Initialize archive object
    $zip = new \ZipArchive();
    $zip->open($zipName, \ZipArchive::CREATE |  \ZipArchive::OVERWRITE);

    // Create recursive directory iterator
    /** @var SplFileInfo[] $files */
    $files = new \RecursiveIteratorIterator(
      new \RecursiveDirectoryIterator($rootPath),
      \RecursiveIteratorIterator::LEAVES_ONLY
    );

    foreach ($files as $name => $file) {
      // Skip directories (they would be added automatically)
      if (!$file->isDir()) {
        // Get real and relative path for current file
        $filePath = $file->getRealPath();
        $relativePath = substr($filePath, strlen($rootPath) + 1);

        // Add current file to archive
        $zip->addFile($filePath, $relativePath);
      }
    }
    $zip->close();

    header("Content-Description: File Transfer");
    header('Content-Disposition: attachment; filename="'.$this->serieTitle.'test .zip"');
    header("Content-type: application/octet-stream");
    header("Content-Length: ".filesize($zipName));
    readfile($zipName);
    delete($zipName);
    die();
  }
}
