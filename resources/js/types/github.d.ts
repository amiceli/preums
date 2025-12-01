export type GithubSearchResult = {
    totalCount: number;
    items: Array<{
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
        owner: {
            login: string;
            id: number;
            avatarUrl: string;
        };
    }>;
};
