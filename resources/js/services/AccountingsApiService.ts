import http from "@/http-common";
import { Accounting, AccountingsResponse } from "@/types/AccountingsType";
import { AxiosResponse } from "axios";

class AuthApiService {
    async getAccountings(): Promise<Accounting[]> {
        const accountings = await http
            .get<AccountingsResponse>("/accountings")
            .then((response: AccountingsResponse) => {
                var accountings: Accounting[] = [];
                response.data.forEach((data: Accounting) => {
                    var accounting: Accounting = {
                        name: "",
                        price: null,
                        deadline: "",
                        is_paid: null,
                        datetime: "",
                    };
                    accounting.name = data.name;
                    accounting.price = data.price;
                    accounting.deadline = data.deadline;
                    accounting.is_paid = data.is_paid;
                    accounting.datetime = data.datetime;
                    accountings.push(accounting);
                });
                return accountings;
            })
            .catch((e: Error) => {
                console.log(e);
            });
        return accountings;
    }
}

export default new AuthApiService();
