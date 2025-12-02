export type GithubSearchResultItem = {
    id: number;
    stars: number;
    name: string;
    fullName: string;
    description: string;
    url: string;
    createdAt: Date;
    updatedAt: Date;
    language: string;
    topics: string[];
    watchers: number;
    forks: number;
    createdAtStr: string;
    updatedAtStr: string;
    owner: {
        login: string;
        id: number;
        avatarUrl: string;
    };
};

export type GithubSearchResult = {
    totalCount: number;
    items: Array<GithubSearchResultItem>;
};
