// Github types

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

export type Road = {
    builtOn: Record<string, GithubRepository>
    beautifulCode: Record<string, GithubRepository>
    UiUx: Record<string, GithubRepository>
    cleanRepo: Record<string, GithubRepository>
    surprises: Record<string, GithubRepository>
}

// prolang

export type ProLangAuthor = {
    birthDate: string | null
    country: string | null
    apiId: string
    link: string | null
    name: string
    pictureUrl: string | null
}

export type ProLangLanguage = {
    id: number
    apiId: string
    company: string | null
    link: string | null
    pictureUrl: string | null
    name: string
    years: string
    authors: ProLangAuthor[]
    mainRepository: string | null
    codeTitle: string
    rawCode: string | null
    rawCodeLink: string | null
    children: Array<Omit<ProLangLanguage, "childran" | "parent">>
    parents: Array<Omit<ProLangLanguage, "childran" | "parent">>
    paths: string[]
}

export type YearGroup = {
    name: string
    position: number
    apiId: string
    languages: ProLangLanguage[]
}
