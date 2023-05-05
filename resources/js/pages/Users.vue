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
                                { field: 'part_formatted', type: 'asc' },
                            ],
                        }"
                        :pagination-options="{
                            enabled: true,
                            perPage: 20,
                        }"
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
import { VueGoodTable } from "vue-good-table-next";
import { useUsers } from "@/composable/useUsers";

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
                    field: "part_formatted",
                    sortFn: this.sortPart,
                },
                {
                    label: "氏名",
                    field: "full_name",
                    sortable: false,
                },
                {
                    label: "在団 / 休団",
                    field: "status_formatted",
                    sortFn: this.sortStatus,
                },
            ],
        };
    },
    setup() {
        const { users, fetchUsers } = useUsers();
        const isFullScreenLoading = ref<Boolean>(false);
        return { users, fetchUsers, isFullScreenLoading };
    },
    methods: {
        sortPart(x: string, y: string, _col, _rowX, _rowY) {
            const part_order = ["Soprano", "Alto", "Tenor", "Bass"];
            const x_index = part_order.indexOf(x);
            const y_index = part_order.indexOf(y);
            return x_index < y_index ? -1 : x_index > y_index ? 1 : 0;
        },
        sortStatus(x: string, y: string, _col, _rowX, _rowY) {
            const status_order = ["在団", "休団"];
            const x_index = status_order.indexOf(x);
            const y_index = status_order.indexOf(y);
            return x_index < y_index ? -1 : x_index > y_index ? 1 : 0;
        },
    },
    async mounted() {
        this.isFullScreenLoading = true;
        await this.fetchUsers();
        this.isFullScreenLoading = false;
    },
});
</script>
