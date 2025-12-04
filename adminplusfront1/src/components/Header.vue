<template>
  <header class="bg-white border-b border-gray-200 px-6 py-4 fixed top-0 left-0 lg:left-64 right-0 z-30">
    <div class="flex items-center justify-between">
      <div class="flex items-center gap-4">
        <button
          @click="$emit('toggle-sidebar')"
          class="lg:hidden p-2 hover:bg-gray-100 rounded-lg transition-colors"
        >
          <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
          </svg>
        </button>

        <div>
          <h2 class="text-2xl font-bold text-gray-900">{{ pageTitle }}</h2>
          <p class="text-sm text-gray-500">{{ currentDate }}</p>
        </div>
      </div>

      <div class="flex items-center gap-4">
        <div class="relative hidden md:block">
          <input
            type="text"
            placeholder="Rechercher..."
            class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent outline-none w-64"
          />
          <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
          </svg>
        </div>

        <button class="relative p-2 hover:bg-gray-100 rounded-lg transition-colors">
          <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
          </svg>
          <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
        </button>

        <div class="flex items-center gap-3 pl-4 border-l border-gray-200">
          <div class="text-right hidden sm:block">
            <p class="text-sm font-semibold text-gray-900">Admin Principal</p>
            <p class="text-xs text-gray-500">admin@benin.gov</p>
          </div>
          <div class="w-10 h-10 bg-gradient-to-br from-primary-500 to-primary-700 rounded-full flex items-center justify-center text-white font-semibold">
            AP
          </div>
        </div>
      </div>
    </div>
  </header>
</template>

<script setup lang="ts">
import { computed } from 'vue'

defineProps<{
  pageTitle: string
}>()

defineEmits(['toggle-sidebar'])

const currentDate = computed(() => {
  const options: Intl.DateTimeFormatOptions = {
    weekday: 'long',
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  }
  return new Date().toLocaleDateString('fr-FR', options)
})
</script>
