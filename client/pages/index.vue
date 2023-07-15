<template>
  <div
    class="stats stats-vertical md:stats-horizontal w-full max-w-sm md:max-w-3xl"
  >
    <div class="stat">
      <div class="stat-figure text-primary">
        <font-awesome-icon icon="wallet" class="text-3xl" />
      </div>
      <div class="stat-title">個別会計残高</div>
      <div class="stat-value w-40">
        {{
          new Intl.NumberFormat("ja-JP", {
            style: "currency",
            currency: "JPY",
          }).format(paymentInfo.balance)
        }}
      </div>
    </div>

    <div class="stat">
      <div class="stat-figure text-secondary">
        <font-awesome-icon icon="money-check-dollar" class="text-3xl" />
      </div>
      <div class="stat-title">滞納額</div>
      <div class="stat-value w-40">
        {{
          new Intl.NumberFormat("ja-JP", {
            style: "currency",
            currency: "JPY",
          }).format(paymentInfo.arrears)
        }}
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
definePageMeta({
  middleware: "auth",
});

const { paymentInfo, getPaymentInfo } = usePaymentInfo();
await getPaymentInfo();
</script>
