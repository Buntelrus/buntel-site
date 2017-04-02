function bs() {
  this.container = document.getElementById('season-container'),
      this.serie = {
        title: '',
        seasons: {}
      }
};

bs.prototype.sid = function (index) {
  return 's-' + index
}
bs.prototype.eid = function (index) {
  return 'e-' + index
}
bs.prototype.setIframe = function (url) {
  if (typeof this.iframe == 'undefined') {
    this.iframe = document.createElement('iframe');
    this.iframe.style.display = 'none';
    document.body.appendChild(this.iframe)
  }
  this.iframe.onload = this.onIframeload;
  this.iframe.src = '/index.php?r=bs%2Fiframe&ep-url=' + encodeURI(url);
};

bs.prototype.onIframeload = function () {
  console.log('iframe loaded');
}

bs.prototype.setDefaultXhrHandler = function (xhr) {
  /* @var XMLHttpRequest xhr */
  xhr.onerror = function (e) {
    alert('XHR Error');
  }
  xhr.onload = function (e) {
    console.log('XHR ready');
  }
};

bs.prototype.addSeasons = function (seasons) {
  seasons.forEach(function (season) {
    console.log(season);
    var seasonTag = document.createElement('div');
    seasonTag.classList.add('season');
    seasonTag.id = BS.sid(season.index);
    var i = season.index + 1;
    var html = '<p><span class="index">' + i + '</span>Staffel ' + i + '</p>';
    seasonTag.innerHTML = html;

    season.element = seasonTag;
    BS.container.appendChild(seasonTag);
  });
}

bs.prototype.addEpisodes = function (episodes) {
  if (typeof this.iframe == 'undefined') {
    this.job = new job(this.getVideoFileDownload, episodes);
  } else {
    this.job.add(episodes);
  }
  episodes.forEach(function (episode) {
    var episodeTag = document.createElement('div');
    episodeTag.classList.add('episode');
    episodeTag.id = BS.eid(episode.index);
    if (episode.saved == 'cache') {
      episodeTag.classList.add('ready');
    }
    var html = '<div class="progress"></div>' +
      '<p><span class="index">' + (episode.index + 1) + '</span>' + episode.title + '</p>';
    episodeTag.innerHTML = html;

    episode.element = episodeTag;
    episode.element.firstChild.style.width = '0%';
    BS.serie.seasons[episode.season].element.appendChild(episodeTag);
  });
};

bs.prototype.getEpisodes = function () {
  BS.serie.seasons.forEach(function (season) {
    var formData = new FormData();
    formData.append('BSEpisodes[seasonIndex]', season.index);
    formData.append('_csrf', document.getElementsByName('csrf-token')[0].content);
    var xhr = new XMLHttpRequest();
    BS.setDefaultXhrHandler(xhr);
    xhr.open('POST', '/index.php?r=bs%2Fparse-episodes');
    xhr.onload = function () {
      console.log('ready episodes');
      var response = parseJson(xhr.responseText);
      if (!response) return;
      BS.serie.seasons[season.index].episodes = response;
      BS.addEpisodes(response);
    };
    xhr.send(formData);
  });
};

bs.prototype.downloadEpisode = function (episode, callback) {
  console.log('download:');
  console.log(episode);
  var formData = new FormData();
  formData.append('BSUrl[seriesUrl]', episode.file);
  formData.append('BSUrl[seasonIndex]', episode.season);
  formData.append('BSUrl[episodeIndex]', episode.index);
  formData.append('_csrf', document.getElementsByName('csrf-token')[0].content);

  var xhr = new XMLHttpRequest();
  this.setDefaultXhrHandler(xhr);
  xhr.open('POST', '/index.php?r=bs%2Fdownload');

  xhr.onreadystatechange = function () {
    if (this.status == 200 && this.readyState != 2) {
      if (typeof this.oldResponseText == 'undefined') this.oldResponseText = '';
      var response = parseJson(this.responseText.replace(this.oldResponseText, ''));

      episode.element.firstChild.style.width = response.progress + '%';
      console.log(response);
      this.oldResponseText = this.responseText;
    }
  }
  xhr.onload = function () {
    callback();
    if (this.oldResponseText && this.responseText) {
      var response = parseJson(this.responseText.replace(this.oldResponseText, ''));
      if (!response) return;

      if (response['season'] == 'finish') {
        BS.serie.seasons[episode.season].element.classList.add('ready');
      }
      if (response['serie'] == 'finish') {
        document.getElementById('step2').classList.add('ready');
      }

      if (response.saved = 'failure') {
        element.classList.add('failure');
      } else {
        episode.classList.add('ready');
      }
    }
    console.log(response);
  };

  xhr.send(formData);
}
bs.prototype.getSeasons = function (form) {
  var formData = new FormData(form);

  var xhr = new XMLHttpRequest();
  BS.setDefaultXhrHandler(xhr);
  xhr.open('POST', '/index.php?r=bs%2Findex');
  xhr.onload = function () {
    var response = parseJson(xhr.responseText);
    if (!response) return;
    BS.serie = response;
    BS.addSeasons(BS.serie.seasons);
    BS.getEpisodes();
    document.getElementById('step1').classList.add('ready');
  }
  xhr.send(formData);
};
bs.prototype.getVideoFileDownload = function (episode, callback) {
  BS.onIframeload = function () {
    var streamurl = 'https://openload.co/stream/';
    var streamid = BS.iframe.contentWindow.document.getElementById('streamurl').innerText;
    episode.file = streamurl + streamid;
    console.log(episode);
    BS.downloadEpisode(episode, function () {
      callback();
    });
  };
  BS.setIframe(episode.href)
}

BS = new bs();

function job(callback, queue) {
  this.queue = queue.filter(this.filter);
  this.callback = callback;
  this.state;
  this.start();
}
job.prototype.start = function () {
  if (this.state == 'running') return false;
  if (this.queue.length > 0) {
    this.state = 'running';
    this.process();
    return true;
  } else {
    return false;
  }
};
job.prototype.process = function () {
  function callback() {
    if (this.queue.length > 0) {
      this.process();
    } else {
      this.state = 'finished';
    }
  }

  this.callback(this.queue.shift(), callback.bind(this));
};
job.prototype.add = function (array, start) {
  this.queue = this.queue.concat(array.filter(this.filter));
  if (typeof start == 'undefined' || start != false) {
    this.start();
  }
};
job.prototype.filter = function (e) {
  if (e.saved == 'not-available') return 1
};
function parseJson(json) {
  try {
    return JSON.parse(json);
  } catch (e) {
    console.error('NOT JSON: ' + json);
    return null
  }
}
$(function () {
  $('#w0').on('submit', function (e) {
    console.log(e.target);
    console.log(e.currentTarget);
    $(this).attr('disabled', true);
    e.preventDefault();
    e.stopImmediatePropagation();
    BS.getSeasons(e.currentTarget);
  });
});
