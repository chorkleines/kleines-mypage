type Accounting = {
  id: number;
  price: number;
  isPaid: boolean;
  datetime: string;
  accountingList: AccountingList;
  accountingPayments: AccountingPayment[];
  priceFormatted: string;
  isOverdue: boolean;
  status: string;
  datetimeFormatted: string;
};

type AccountingList = {
  id: number;
  name: string;
  deadline: string;
  admin: string;
  deadlineFormatted: string;
};

type AccountingPayment = {
  id: number;
  price: number;
  method: string;
  priceFormatted: string;
  methodFormatted: string;
};

const createAccounting = (a) => {
  let accounting: Accounting | null = null;
  if (a === null) {
    accounting = null;
  } else {
    accounting = {
      id: a.id,
      price: a.price,
      isPaid: a.is_paid,
      datetime: a.datetime,
      accountingList: {
        id: a.accounting_list.id,
        name: a.accounting_list.name,
        deadline: a.accounting_list.deadline,
        admin: a.accounting_list.admin,
      },
      accountingPayments: [],
    };
    a.accounting_payments.forEach((p) => {
      const payment = createPayment(p);
      accounting.accountingPayments.push(payment);
    });
  }
  return accounting;
};

const createPayment = (p) => {
  let payment: AccountingPayment | null = null;
  if (p === null) {
    payment = null;
  } else {
    payment = {
      id: p.id,
      price: p.price,
      method: p.method,
    };
  }
  return payment;
};

export const useAccounting = () => {
  const accounting = ref({
    id: null,
    price: null,
    isPaid: null,
    datetime: "",
    accountingList: {
      id: null,
      name: "",
      deadline: "",
      admin: "",
    },
    accountingPayments: [],
  });

  async function getAccounting(id: number) {
    const { data } = await useApiFetch(`/api/accountings/${id}`, {
      method: "GET",
    });

    accounting.value = createAccounting(data.value);
    accounting.value.priceFormatted = new Intl.NumberFormat("ja-JP", {
      style: "currency",
      currency: "JPY",
    }).format(accounting.value.price);
    accounting.value.isOverdue =
      !accounting.value.isPaid &&
      Date.now() > new Date(accounting.value.accountingList.deadline).getTime();
    accounting.value.status = accounting.value.isPaid
      ? "支払い済み"
      : accounting.value.isOverdue
      ? "期限切れ"
      : "未払い";
    accounting.value.datetimeFormatted = new Date(
      accounting.value.datetime,
    ).toLocaleString();
    accounting.value.accountingList.deadlineFormatted = new Date(
      accounting.value.accountingList.deadline,
    ).toLocaleDateString();

    if (accounting.value.isPaid) {
      accounting.value.accountingPayments.forEach(
        (payment: AccountingPayment) => {
          payment.priceFormatted = new Intl.NumberFormat("ja-JP", {
            style: "currency",
            currency: "JPY",
          }).format(payment.price);
          switch (payment.method) {
            case "CASH":
              payment.methodFormatted = "現金";
              break;
            case "INDIVIDUAL_ACCOUNTING":
              payment.methodFormatted = "個別会計";
              break;
            default:
              payment.methodFormatted = "";
              break;
          }
        },
      );
    }
  }

  return {
    accounting,
    getAccounting,
  };
};
