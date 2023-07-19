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

export const useAccountings = () => {
  const accountings = ref([]);

  async function getAccountings() {
    const { data } = await useApiFetch("/api/accountings", {
      method: "GET",
    });
    data.value.forEach((a) => {
      const accounting = createAccounting(a);
      accountings.value.push(accounting);
    });

    accountings.value.forEach((accounting: Accounting) => {
      accounting.priceFormatted = new Intl.NumberFormat("ja-JP", {
        style: "currency",
        currency: "JPY",
      }).format(accounting.price);
      accounting.isOverdue =
        !accounting.isPaid &&
        Date.now() > new Date(accounting.deadline).getTime();
      accounting.status = accounting.isPaid
        ? "支払い済み"
        : accounting.isOverdue
        ? "期限切れ"
        : "未払い";
      accounting.datetimeFormatted = new Date(
        accounting.datetime,
      ).toLocaleString();
      accounting.deadlineFormatted = new Date(
        accounting.deadline,
      ).toLocaleDateString();
    });

    accountings.value.sort(function (a, b) {
      const aDatetime = new Date(a.datetime);
      const bDatetime = new Date(b.datetime);
      const aDeadline = new Date(a.deadline);
      const bDeadline = new Date(b.deadline);
      return (
        a.isPaid - b.isPaid ||
        bDatetime.getTime() - aDatetime.getTime() ||
        bDeadline.getTime() - aDeadline.getTime()
      );
    });
  }

  return {
    accountings,
    getAccountings,
  };
};
