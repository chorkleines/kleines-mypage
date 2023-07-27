type Accounting = {
  id: number;
  price: number;
  isPaid: boolean;
  datetime: string;
  accountingList: AccountingList;
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
        Date.now() > new Date(accounting.accountingList.deadline).getTime();
      accounting.status = accounting.isPaid
        ? "支払い済み"
        : accounting.isOverdue
        ? "期限切れ"
        : "未払い";
      accounting.datetimeFormatted = new Date(
        accounting.datetime,
      ).toLocaleString();
      accounting.accountingList.deadlineFormatted = new Date(
        accounting.accountingList.deadline,
      ).toLocaleDateString();
    });

    accountings.value.sort(function (a, b) {
      const aDatetime = new Date(a.datetime);
      const bDatetime = new Date(b.datetime);
      const aDeadline = new Date(a.accountingList.deadline);
      const bDeadline = new Date(b.accountingList.deadline);
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
