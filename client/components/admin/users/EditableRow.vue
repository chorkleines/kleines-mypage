<template>
  <tr>
    <th class="w-36">
      {{ name }}
      <span
        class="text-error"
        v-if="required && hasRoles(['MASTER', 'MANAGER'])"
        >*</span
      >
    </th>
    <td v-if="!edit && !isLoading">
      <span
        v-if="
          getField(user, formattedField) !== undefined &&
          getField(user, formattedField) !== null
        "
        >{{ getField(user, formattedField) }}</span
      >
      <span v-else class="text-base-content/[.3]">未入力</span>
    </td>
    <td v-else-if="isLoading" class="py-0">
      <span
        class="loading loading-spinner loading-sm inline-block align-middle"
      ></span>
    </td>
    <td v-else class="py-0">
      <slot :updateValue="updateValue" :newValue="newValue" />
    </td>
    <td class="py-0 w-20 text-right" v-if="hasRoles(['MASTER', 'MANAGER'])">
      <a
        class="btn btn-sm"
        v-if="!edit"
        @click="edit = true"
        :class="{ 'btn-disabled': isLoading }"
      >
        <font-awesome-icon icon="pen-to-square" />
      </a>
      <div class="join" v-else>
        <a class="btn btn-sm join-item btn-success" @click="save()">
          <font-awesome-icon icon="floppy-disk" />
        </a>
        <a
          class="btn btn-sm join-item btn-error"
          @click="
            edit = false;
            updateValue(getField(user, field));
          "
        >
          <font-awesome-icon icon="ban" />
        </a>
      </div>
    </td>
  </tr>
</template>

<script setup lang="ts">
const props = defineProps({
  user: {
    type: Object,
    required: true,
  },
  name: {
    type: String,
    required: true,
  },
  field: {
    type: String,
    required: true,
  },
  formattedField: {
    type: String,
    default: (props) => props.field,
  },
  refresh: {
    type: Function,
    default: () => {},
  },
  alerts: {
    type: Array,
    required: true,
  },
  required: {
    type: Boolean,
    default: false,
  },
});

const edit = ref(false);
const isLoading = ref(false);
const newValue = ref(getField(props.user, props.field));
const { updateUser, updateProfile } = useAdminUsers();
const { hasRoles } = useAuth();

const updateValue = (value) => {
  newValue.value = value;
};

const createAlert = async (message, type) => {
  props.alerts.push({ message, type });
  await new Promise((r) => setTimeout(r, 3000));
  const idx = props.alerts.map((e) => e.message).indexOf(message);
  if (idx > -1) {
    props.alerts.splice(idx, 1);
  }
};

const save = async () => {
  edit.value = false;
  isLoading.value = true;
  if (props.required && !newValue.value) {
    isLoading.value = false;
    newValue.value = getField(props.user, props.field);
    await createAlert(`${props.name}は必須です`, "error");
    return;
  }
  if (props.field.includes("profile")) {
    const { status, error } = await updateProfile(props.user.id, {
      [camelToSnakeCase(props.field.split(".")[1])]: newValue.value,
    });
    if (status.value !== "success") {
      isLoading.value = false;
      newValue.value = getField(props.user, props.field);
      await createAlert(
        `${props.name}：${error.value.data.detail[props.field]}`,
        "error",
      );
      return;
    }
  } else {
    const { status, error } = await updateUser(props.user.id, {
      [camelToSnakeCase(props.field)]: newValue.value,
    });
    if (status.value !== "success") {
      isLoading.value = false;
      newValue.value = getField(props.user, props.field);
      await createAlert(
        `${props.name}：${error.value.data.detail[props.field]}`,
        "error",
      );
      return;
    }
  }
  await props.refresh();
  isLoading.value = false;
  await createAlert(`${props.name}を更新しました`, "success");
};

function getField(row: any, field: string) {
  return field.split(".").reduce((acc, cur) => {
    return acc[cur];
  }, row);
}

const camelToSnakeCase = (str) =>
  str.replace(/[A-Z]/g, (letter) => `_${letter.toLowerCase()}`);
</script>
