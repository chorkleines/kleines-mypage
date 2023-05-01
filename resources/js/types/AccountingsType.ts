export type Accounting = {
    name: string;
    price: number;
    deadline: string;
    is_paid: boolean;
    datetime: string;
};

export type AccountingsResponse = Accounting[];
