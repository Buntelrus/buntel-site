export interface IPage {
    slug: string
    title: string
    content: any
}
export interface IMenu {
    url: string,
    text: string
    newTab: boolean
}
export interface IFooter {
    title: string
    columns: IFooterColumn[]
    socialMediaLinks: ISocialMediaLink[]
}
interface IFooterColumn {
    title: string
    links: {
        text: string
        title: string
        url: string
        newTab: boolean
    }[]
}
interface ISocialMediaLink {
        link: string
        type: string
}

export interface IGlobal {
    menu: {
        items: {
            url: string
            text: string
        }[]
    }
    footer: IFooter
}

const pages: IPage[] = [
    {
        slug: 'home',
        title: 'Home',
        content: 'Hello Home'
    },{
        slug: 'projects',
        title: 'Projects',
        content: 'Hello Projects'
    },{
        slug: 'skills',
        title: 'Skills',
        content: 'Hello Skill'
    },{
        slug: 'contact',
        title: 'Contact',
        content: 'Hello Contact'
    },{
        slug: 'about',
        title: 'About',
        content: 'Hello About'
    },
]
const global: IGlobal = {
    menu: {
        items: []
    },
    footer: {
        title: 'My Footer',
        columns: [
            {
                title: 'Column 1',
                links: [{
                    text: 'Link 1',
                    title: 'Link1',
                    url: '#',
                    newTab: false,
                },
                    {
                        text: 'Link 2',
                        title: 'Link2',
                        url: '#',
                        newTab: false,
                    }]
            }, {
                title: 'Column 2',
                links: [{
                    text: 'Link 1',
                    title: 'Link1',
                    url: '#',
                    newTab: false,
                },
                    {
                        text: 'Link 2',
                        title: 'Link2',
                        url: '#',
                        newTab: false,
                    }]
            }
        ],
        socialMediaLinks: [
            {
                type: 'twitter',
                link: '#'
            }, {
                type: 'youtube',
                link: '#'
            }, {
                type: 'facebook',
                link: '#'
            }, {
                type: 'instagram',
                link: '#'
            },
        ]
    }
}

const api = {
    global,
    pages
}

export default function<T extends keyof typeof api>(path: T): Promise<typeof api[T]> {
    return new Promise(resolve => {
        setTimeout(() => resolve(api[path]), 500)
    })
}
