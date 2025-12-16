export type GithubSearchResultItem = {
    id: number
    stars: number
    name: string
    fullName: string
    description: string
    url: string
    createdAt: Date
    updatedAt: Date
    language: string
    topics: string[]
    watchers: number
    forks: number
    createdAtStr: string
    updatedAtStr: string
    owner: {
        login: string
        id: number
        avatarUrl: string
    }
    ownerIsOrganization: boolean
}

export type GithubSearchResult = {
    totalCount: number
    items: Array<GithubSearchResultItem>
}

export type GithubCommitDiff = {
    y: number
    m: number
    d: number
    days: number
}

export type GithubCommitActivity = {
    totalCommits: number
    days: {
        [key: string]: number
    }
}

export type GithubCommit = {
    dateStr: string
    author: string
    authorUrl: string
    authorImg: string
    message: string
    sha: string
    url: string
}

export type GithubRelease = {
    dateStr: string
    author: string
    body: string
    name: string
    url: string
    reactions: Record<string, number>
    authorUrl: string
    authorImg: string
}
