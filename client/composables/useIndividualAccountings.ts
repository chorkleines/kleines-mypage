export type IndividualAccounting = {
  createdAt: string;
  datetime: string;
  name: string;
  price: number;
  priceFormatted: string;
  datetimeFormatted: string;
  accountingId: string;
};

const createIndividualAccounting = (a) => {
  let individualAccounting: IndividualAccounting | null = null;
  if (a === null) {
    individualAccounting = null;
  } else {
    individualAccounting = {
      createdAt: a.created_at,
      datetime: a.datetime,
      name: a.name,
      price: a.price,
      accountingId: a.accounting_id,
    };
  }
  return individualAccounting;
};

export const useIndividualAccountings = () => {
  const { getJWT } = useAuth();
  const individualAccountings = ref([]);

  async function getIndividualAccountings() {
    const { data } = await useFetch("/api/individual_accountings", {
      baseURL: "http://localhost:8000",
      method: "GET",
      headers: {
        Authorization: `Bearer ${getJWT()}`,
      },
    });
    data.value.forEach((a) => {
      const individualAccounting = createIndividualAccounting(a);
      individualAccountings.value.push(individualAccounting);
    });

    individualAccountings.value.forEach(
      (individualAccounting: IndividualAccounting) => {
        individualAccounting.priceFormatted = new Intl.NumberFormat("ja-JP", {
          style: "currency",
          currency: "JPY",
        }).format(individualAccounting.price);
        individualAccounting.datetimeFormatted = new Date(
          individualAccounting.datetime,
        ).toLocaleString();
      },
    );
  }

  return {
    individualAccountings,
    getIndividualAccountings,
  };
};
