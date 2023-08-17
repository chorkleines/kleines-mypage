<template>
  <div class="flex flex-col gap-2">
    <select
      class="select select-sm select-bordered w-20"
      @change="currentPerPage = $event.target.value"
    >
      <option
        v-for="perPage in perPageOptions"
        :selected="perPage === currentPerPage"
        :value="perPage"
      >
        <span v-if="perPage === -1">全件</span>
        <span v-else>{{ perPage }}</span>
      </option>
    </select>
  </div>
</template>

<script setup lang="ts">
const props = defineProps({
  perPageOptions: {
    type: Array,
    default: () => [10, 15, 20, 50, -1],
  },
});
const currentPerPage = useState<Number>("currentPerPage");
</script>
