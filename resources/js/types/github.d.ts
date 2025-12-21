export type GithubOwner = {
    login: string
    avatarUrl: string
    url: string
}

export type GithubUser = GithubOwner & {
    createdAtStr: string
    url: string
    location: string | null
    blog: string | null
    company: string | null
    countRepos: number
    followers: number
    following: number
    createdAt: Date
}

export type GithubRepository = {
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
    owner: GithubOwner
    ownerIsOrganization: boolean
}

export type GithubOrg = {
    createdAtStr: string
    updatedAtStr: string
    url: string
    avatarUrl: string
    name: string
    countRepos: number
    followers: number
    createdAt: Date
    updatedAt: Date
    location: string
    blog: string | null
}

export type GithubSearchResult = {
    totalCount: number
    items: Array<GithubRepository>
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
    authorLogin: string
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

export type LangStats = {
    type: string
    name: string
    isoCode: string
    pushers: number
    year: number
}
