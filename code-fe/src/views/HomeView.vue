<template>
  <div class="min-h-full bg-gray-50">
    <header class="bg-white shadow">
      <div class="mx-auto max-w-7xl py-6 px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold tracking-tight text-gray-900">User Management</h1>
      </div>
    </header>

    <main>
      <div class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8">
        <!-- User Form Modal -->
        <TransitionRoot as="template" :show="showModal">
          <Dialog as="div" class="relative z-10" @close="closeModal">
            <TransitionChild
              enter="ease-out duration-300"
              enter-from="opacity-0"
              enter-to="opacity-100"
              leave="ease-in duration-200"
              leave-from="opacity-100"
              leave-to="opacity-0"
            >
              <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" />
            </TransitionChild>

            <div class="fixed inset-0 z-10 overflow-y-auto">
              <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <TransitionChild
                  enter="ease-out duration-300"
                  enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                  enter-to="opacity-100 translate-y-0 sm:scale-100"
                  leave="ease-in duration-200"
                  leave-from="opacity-100 translate-y-0 sm:scale-100"
                  leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                >
                  <DialogPanel class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
                    <div class="absolute right-0 top-0 hidden pr-4 pt-4 sm:block">
                      <button
                        type="button"
                        class="rounded-md bg-white text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2"
                        @click="closeModal"
                      >
                        <span class="sr-only">Close</span>
                        <XMarkIcon class="h-6 w-6" aria-hidden="true" />
                      </button>
                    </div>
                    <div class="sm:flex sm:items-start">
                      <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                        <DialogTitle as="h3" class="text-lg font-semibold leading-6 text-gray-900">
                          {{ selectedUser ? 'Edit User' : 'Create New User' }}
                        </DialogTitle>
                        <div class="mt-6">
                          <UserForm
                            :user="selectedUser"
                            :is-edit="!!selectedUser"
                            @submit="handleSubmit"
                          />
                        </div>
                      </div>
                    </div>
                  </DialogPanel>
                </TransitionChild>
              </div>
            </div>
          </Dialog>
        </TransitionRoot>

        <!-- User List -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
          <UserList
            :users="users"
            @add="openModal()"
            @edit="openModal($event)"
            @delete="handleDelete"
          />
        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue'
import { XMarkIcon } from '@heroicons/vue/24/outline'
import UserList from '@/components/UserList.vue'
import UserForm from '@/components/UserForm.vue'
import { userService } from '@/services/userService'

const users = ref([])
const showModal = ref(false)
const selectedUser = ref(null)

onMounted(async () => {
  await fetchUsers()
})

const fetchUsers = async () => {
  try {
    const response = await userService.getUsers()
    users.value = response.data.data
  } catch (error) {
    console.error('Error fetching users:', error)
  }
}

const openModal = (user = null) => {
  selectedUser.value = user
  showModal.value = true
}

const closeModal = () => {
  selectedUser.value = null
  showModal.value = false
}

const handleSubmit = async (formData) => {
  try {
    if (selectedUser.value) {
      await userService.updateUser(selectedUser.value.id, formData)
    } else {
      await userService.createUser(formData)
    }
    await fetchUsers()
    closeModal()
  } catch (error) {
    console.error('Error saving user:', error)
  }
}

const handleDelete = async (user) => {
  if (confirm('Are you sure you want to delete this user?')) {
    try {
      await userService.deleteUser(user.id)
      await fetchUsers()
    } catch (error) {
      console.error('Error deleting user:', error)
    }
  }
}
</script>