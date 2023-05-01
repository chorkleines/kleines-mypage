<template>
    <App :isFullScreenLoading="isFullScreenLoading">
        <template v-slot:content>
            <div class="prose">
                <h1>団員リスト</h1>
                <div class="overflow-x-auto w-full not-prose">
                    <vue-good-table
                        :columns="columns"
                        :rows="users"
                        :sort-options="{
                            enabled: true,
                            initialSortBy: [
                                { field: 'grade', type: 'desc' },
                                { field: 'computed_part', type: 'asc' },
                            ],
                        }"
                        :pagination-options="{
                            enabled: true,
                        }"
                        :line-numbers="true"
                        styleClass="table text-nowrap w-full shadow-md mb-3"
                    />
                </div>
            </div>
        </template>
    </App>
</template>

<script lang="ts">
import { ref, defineComponent } from "vue";
import App from "@/components/App.vue";
import UsersApiService from "@/services/UsersApiService";
import { User } from "@/types/UsersType";
import { VueGoodTable } from "vue-good-table-next";

export default defineComponent({
    components: {
        App,
        VueGoodTable,
    },
    data() {
        return {
            columns: [
                {
                    label: "学年",
                    field: "grade",
                },
                {
                    label: "パート",
                    field: "computed_part",
                    sortFn: this.sortPart,
                },
                {
                    label: "氏名",
                    field: "last_name",
                    sortable: false,
                },
                {
                    label: "在団 / 休団",
                    field: "computed_status",
                    sortFn: this.sortStatus,
                },
            ],
        };
    },
    setup() {
        const users = ref<User[]>([]);
        const isFullScreenLoading = ref<Boolean>(false);
        return { users, isFullScreenLoading };
    },
    methods: {
        async getUsers() {
            this.users = await UsersApiService.getUsers();
            this.users.forEach((user: User) => {
                switch (user.status) {
                    case "PRESENT":
                        user.computed_status = "在団";
                        break;
                    case "ABSENT":
                        user.computed_status = "休団";
                        break;
                    default:
                        user.computed_status = "";
                        break;
                }
                switch (user.part) {
                    case "S":
                        user.computed_part = "Soprano";
                        break;
                    case "A":
                        user.computed_part = "Alto";
                    case "T":
                        user.computed_part = "Tenor";
                    case "B":
                        user.computed_part = "Bass";
                        break;
                    default:
                        user.computed_part = "";
                        break;
                }
            });
        },
        sortPart(x, y, _col, _rowX, _rowY) {
            const part_order = ["Soprano", "Alto", "Tenor", "Bass"];
            const x_index = part_order.indexOf(x);
            const y_index = part_order.indexOf(y);
            return x_index < y_index ? -1 : x_index > y_index ? 1 : 0;
        },
        sortStatus(x, y, _col, _rowX, _rowY) {
            const status_order = ["在団", "休団"];
            const x_index = status_order.indexOf(x);
            const y_index = status_order.indexOf(y);
            return x_index < y_index ? -1 : x_index > y_index ? 1 : 0;
        },
    },
    async mounted() {
        this.isFullScreenLoading = true;
        await this.getUsers();
        this.isFullScreenLoading = false;
    },
});
</script>
