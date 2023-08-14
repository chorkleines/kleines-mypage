<template>
  <div class="flex flex-col justify-center items-center gap-2">
    <div class="join">
      <div v-for="(paginationLabel, _paginationIndex) in paginationLabels">
        <button
          class="join-item btn btn-sm"
          :class="{
            'btn-disabled': paginationLabel === '...',
            'btn-primary': paginationLabel === currentPageIndex + 1,
          }"
          v-if="paginationLabel === '...'"
        >
          <font-awesome-icon icon="ellipsis" />
        </button>
        <button
          class="join-item btn btn-sm"
          :class="{
            'btn-disabled': paginationLabel === '...',
            'btn-primary': paginationLabel === currentPageIndex + 1,
          }"
          v-else-if="paginationLabel === '<'"
          @click="currentPageIndex = currentPageIndex - 1"
        >
          <font-awesome-icon icon="chevron-left" />
        </button>
        <button
          class="join-item btn btn-sm"
          :class="{
            'btn-disabled': paginationLabel === '...',
            'btn-primary': paginationLabel === currentPageIndex + 1,
          }"
          v-else-if="paginationLabel === '>'"
          @click="currentPageIndex = currentPageIndex + 1"
        >
          <font-awesome-icon icon="chevron-right" />
        </button>
        <button
          class="join-item btn btn-sm"
          :class="{
            'btn-disabled': paginationLabel === '...',
            'btn-primary': paginationLabel === currentPageIndex + 1,
          }"
          v-else
          @click="currentPageIndex = paginationLabel - 1"
        >
          <span>
            {{ paginationLabel }}
          </span>
        </button>
      </div>
    </div>
    <div class="text-sm">
      {{ total }} 件中 {{ currentPageIndex * perPage + 1 }} 〜
      {{ Math.min((currentPageIndex + 1) * perPage, total) }} 件
    </div>
  </div>
</template>

<script setup lang="ts">
const props = defineProps({
  total: {
    type: Number,
    required: true,
  },
  perPage: {
    type: Number,
    required: true,
  },
});

const currentPageIndex = useState<Number>("currentPageIndex");

const paginationLabels = computed(() => {
  const paginationLabels = [];
  const pageIndex = currentPageIndex.value;
  const perPage = props.perPage;
  const total = props.total;
  const pageTotal = Math.ceil(total / perPage);

  if (pageTotal <= 5) {
    for (let i = 0; i < pageTotal; i++) {
      paginationLabels.push(i + 1);
    }
  } else {
    if (pageIndex < 2) {
      for (let i = 0; i < 3; i++) {
        paginationLabels.push(i + 1);
      }
      paginationLabels.push("...");
      paginationLabels.push(pageTotal);
      paginationLabels.push(">");
    } else if (pageIndex > pageTotal - 3) {
      paginationLabels.push("<");
      paginationLabels.push(1);
      paginationLabels.push("...");
      for (let i = pageTotal - 3; i < pageTotal; i++) {
        paginationLabels.push(i + 1);
      }
    } else {
      paginationLabels.push("<");
      paginationLabels.push(1);
      paginationLabels.push("...");
      for (let i = pageIndex - 1; i < pageIndex + 2; i++) {
        paginationLabels.push(i + 1);
      }
      paginationLabels.push("...");
      paginationLabels.push(pageTotal);
      paginationLabels.push(">");
    }
  }

  return paginationLabels;
});
</script>
