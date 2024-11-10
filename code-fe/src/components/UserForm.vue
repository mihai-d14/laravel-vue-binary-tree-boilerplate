<template>
  <form @submit.prevent="handleSubmit" class="space-y-6">
    <div>
      <label for="name" class="block text-sm font-medium leading-6 text-gray-900">Name</label>
      <div class="mt-2">
        <input
          type="text"
          id="name"
          v-model="form.name"
          class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6"
          required
        />
      </div>
    </div>

    <div>
      <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Email</label>
      <div class="mt-2">
        <input
          type="email"
          id="email"
          v-model="form.email"
          class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6"
          required
        />
      </div>
    </div>

    <div>
      <label for="treeType" class="block text-sm font-medium leading-6 text-gray-900">Tree Type</label>
      <div class="mt-2">
        <select
          id="treeType"
          v-model="form.tree_type"
          class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6"
          required
        >
          <option value="bst">Binary Search Tree</option>
          <option value="avl">AVL Tree</option>
        </select>
      </div>
    </div>

    <div class="mt-6 flex items-center justify-end gap-x-6">
      <button
        type="button"
        class="text-sm font-semibold leading-6 text-gray-900"
        @click="$emit('cancel')"
      >
        Cancel
      </button>
      <button
        type="submit"
        class="rounded-md bg-primary-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600"
      >
        {{ isEdit ? 'Update' : 'Create' }}
      </button>
    </div>
  </form>
</template>

<script setup>
import { ref, onMounted } from 'vue'

const props = defineProps({
  user: {
    type: Object,
    default: () => ({})
  },
  isEdit: {
    type: Boolean,
    default: false
  }
})

const emit = defineEmits(['submit', 'cancel'])

const form = ref({
  name: '',
  email: '',
  tree_type: 'bst'
})

onMounted(() => {
  if (props.user.id) {
    form.value = { ...props.user }
  }
})

const handleSubmit = () => {
  emit('submit', form.value)
}
</script>