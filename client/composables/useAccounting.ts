export type Accounting = {
  id: number;
  accountingId: number;
  admin: string;
  createdAt: string;
  datetime: string;
  deadline: string;
  isPaid: boolean;
  name: string;
  price: number;
  priceFormatted: string;
  isOverdue: boolean;
  status: string;
  deadlineFormatted: string;
  datetimeFormatted: string;
};

export type Payment = {
  id: number;
  accountingRecordId: number;
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
      accountingId: a.accounting_id,
      admin: a.admin,
      createdAt: a.created_at,
      datetime: a.datetime,
      deadline: a.deadline,
      isPaid: a.is_paid,
      name: a.name,
      price: a.price,
    };
  }
  return accounting;
};

const createPayment = (p) => {
  let payment: Payment | null = null;
  if (p === null) {
    payment = null;
  } else {
    payment = {
      id: p.id,
      accountingRecordId: p.accounting_record_id,
      price: p.price,
      method: p.method,
    };
  }
  return payment;
};

export const useAccounting = () => {
  const { getJWT } = useAuth();
  const accounting = ref({
    id: null,
    accountingId: null,
    admin: "",
    name: "",
    price: null,
    deadline: "",
    isPaid: null,
    datetime: "",
  });
  const payments = ref([]);

  async function getAccounting(id: number) {
    const { data } = await useFetch(`/api/accountings/${id}`, {
      baseURL: "http://localhost:8000",
      method: "GET",
      headers: {
        Authorization: `Bearer ${getJWT()}`,
      },
    });

    accounting.value = createAccounting(data.value.accounting);
    accounting.value.priceFormatted = new Intl.NumberFormat("ja-JP", {
      style: "currency",
      currency: "JPY",
    }).format(accounting.value.price);
    accounting.value.isOverdue =
      !accounting.value.isPaid &&
      Date.now() > new Date(accounting.value.deadline).getTime();
    accounting.value.status = accounting.value.isPaid
      ? "支払い済み"
      : accounting.value.isOverdue
      ? "期限切れ"
      : "未払い";
    accounting.value.datetimeFormatted = new Date(
      accounting.value.datetime,
    ).toLocaleString();
    accounting.value.deadlineFormatted = new Date(
      accounting.value.deadline,
    ).toLocaleDateString();
    console.log(accounting.value);

    if (accounting.value.isPaid) {
      data.value.payments.forEach((p) => {
        const payment = createPayment(p);
        payments.value.push(payment);
      });
      payments.value.forEach((payment: Payment) => {
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
      });
    }
  }

  return {
    accounting,
    payments,
    getAccounting,
  };
};
