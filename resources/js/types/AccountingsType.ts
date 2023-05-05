export type Accounting = {
    id: number;
    accounting_id: number;
    admin: string;
    name: string;
    price: number;
    deadline: string;
    is_paid: boolean;
    datetime: string;
};

export type ComputedAccounting = Accounting & {
    price_formatted: string;
    is_overdue: boolean;
    status: string;
};
