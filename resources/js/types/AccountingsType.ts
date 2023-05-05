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
    deadline_formatted: string;
    datetime_formatted: string;
};

export type Payment = {
    id: number;
    accounting_record_id: number;
    price: number;
    method: string;
};

export type ComputedPayment = Payment & {
    price_formatted: string;
    method_formatted: string;
};
