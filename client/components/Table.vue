<template>
  <TableForm class="mb-3" />
  <div class="overflow-x-auto not-prose mb-3">
    <table class="table">
      <thead>
        <tr>
          <th
            v-for="(column, _columnIndex) in columns"
            :class="[
              column.headClass,
              column.sortable !== false ? 'table-sort' : '',
              sortProps.field === column.field
                ? 'table-sort-' + sortProps.order
                : '',
            ]"
            @click="setSortProps(column.field)"
          >
            {{ column.label
            }}<span v-if="column.sortable !== false">&emsp;</span>
          </th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(row, _rowIndex) in paginationRows">
          <td
            v-for="(column, _columnIndex) in columns"
            :class="column.bodyClass"
            class="whitespace-nowrap"
          >
            <span
              v-if="column.route !== undefined && column.route !== null"
              class="link link-primary font-medium"
            >
              <NuxtLink :to="getField(row, column.route)">{{
                getField(row, column.field)
              }}</NuxtLink>
            </span>
            <span v-else>
              {{ getField(row, column.field) }}
            </span>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
  <TablePagination :total="rows.length" />
</template>

<script setup lang="ts">
import { TablePagination, TableForm } from "#components";

const currentPageIndex = useState<Number>("currentPageIndex", () => {
  return 0;
});
currentPageIndex.value = 0;
const currentPerPage = useState<Number>("currentPerPage", () => {
  return 0;
});
currentPerPage.value = 10;

const props = defineProps({
  columns: {
    type: Array,
    required: true,
  },
  rows: {
    type: Array,
    required: true,
  },
  initialSort: {
    type: Array,
    default: () => [],
  },
  initialPerPage: {
    type: Number,
    default: 10,
  },
});

onMounted(() => {
  props.initialSort.reverse();
  props.initialSort.forEach((sort) => {
    sortField(props.rows, sort.field, sort.type);
  });
  currentPerPage.value = props.initialPerPage;
});

const sortProps = reactive({
  field: null,
  order: null,
});

function sortField(rows, field, order) {
  return rows.sort((a, b) => {
    const fieldA = getField(a, field);
    const fieldB = getField(b, field);
    const column = props.columns.find((column) => column.field === field);
    if (column.sortFn === undefined || column.sortFn === null) {
      if (fieldA < fieldB) {
        return order === "asc" ? -1 : 1;
      }
      if (fieldA > fieldB) {
        return order === "asc" ? 1 : -1;
      }
      return 0;
    } else {
      if (order === "asc") {
        return column.sortFn(fieldA, fieldB);
      } else {
        return column.sortFn(fieldB, fieldA);
      }
    }
  });
}

const sortedRows = computed(() => {
  if (sortProps.field === null || sortProps.order === null) return props.rows;
  const rows = [...props.rows];
  return sortField(rows, sortProps.field, sortProps.order);
});

function setSortProps(field) {
  currentPageIndex.value = 0;
  if (sortProps.field === field) {
    if (sortProps.order === undefined || sortProps.order === null) {
      sortProps.order = "asc";
    } else if (sortProps.order === "asc") {
      sortProps.order = "desc";
    } else {
      sortProps.order = null;
    }
  } else {
    sortProps.field = field;
    sortProps.order = "asc";
  }
}

const paginationRows = computed(() => {
  if (currentPerPage.value == -1) return sortedRows.value;
  return sortedRows.value.slice(
    currentPageIndex.value * currentPerPage.value,
    (currentPageIndex.value + 1) * currentPerPage.value,
  );
});

function getField(row: any, field: string) {
  return field.split(".").reduce((acc, cur) => {
    return acc[cur];
  }, row);
}
</script>

<style scoped>
.table-sort {
  cursor: pointer;
}

.table-sort:after {
  content: "";
  display: inline-block;
  position: absolute;
  width: 0.5rem;
  height: 1rem;
  right: 0.5rem;
  background-color: hsl(var(--bc) / 0.6);
  mask: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' height='1em' viewBox='0 0 320 512'%3E%3C!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --%3E%3Cpath d='M137.4 41.4c12.5-12.5 32.8-12.5 45.3 0l128 128c9.2 9.2 11.9 22.9 6.9 34.9s-16.6 19.8-29.6 19.8H32c-12.9 0-24.6-7.8-29.6-19.8s-2.2-25.7 6.9-34.9l128-128zm0 429.3l-128-128c-9.2-9.2-11.9-22.9-6.9-34.9s16.6-19.8 29.6-19.8H288c12.9 0 24.6 7.8 29.6 19.8s2.2 25.7-6.9 34.9l-128 128c-12.5 12.5-32.8 12.5-45.3 0z'/%3E%3C/svg%3E");
  mask-repeat: no-repeat;
  mask-size: contain;
  mask-position: center;
  margin-right: 0.25rem;
}

.table-sort-asc:after {
  content: "";
  display: inline-block;
  position: absolute;
  width: 0.5rem;
  height: 1rem;
  right: 0.5rem;
  background-color: hsl(var(--bc) / 0.6);
  mask: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' height='1em' viewBox='0 0 320 512'%3E%3C!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --%3E%3Cpath d='M182.6 41.4c-12.5-12.5-32.8-12.5-45.3 0l-128 128c-9.2 9.2-11.9 22.9-6.9 34.9s16.6 19.8 29.6 19.8H288c12.9 0 24.6-7.8 29.6-19.8s2.2-25.7-6.9-34.9l-128-128z'/%3E%3C/svg%3E");
  mask-repeat: no-repeat;
  mask-size: contain;
  mask-position: center;
  margin-right: 0.25rem;
}

.table-sort-desc:after {
  content: "";
  display: inline-block;
  position: absolute;
  width: 0.5rem;
  height: 1rem;
  right: 0.5rem;
  background-color: hsl(var(--bc) / 0.6);
  mask: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' height='1em' viewBox='0 0 320 512'%3E%3C!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --%3E%3Cpath d='M182.6 470.6c-12.5 12.5-32.8 12.5-45.3 0l-128-128c-9.2-9.2-11.9-22.9-6.9-34.9s16.6-19.8 29.6-19.8H288c12.9 0 24.6 7.8 29.6 19.8s2.2 25.7-6.9 34.9l-128 128z'/%3E%3C/svg%3E");
  mask-repeat: no-repeat;
  mask-size: contain;
  mask-position: center;
  margin-right: 0.25rem;
}
</style>
