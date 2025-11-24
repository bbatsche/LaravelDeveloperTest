<script setup>
import AllPosts from '../components/posts/AllPosts.vue';
</script>

<template>
    <Suspense>
        <AllPosts></AllPosts>

        <template #fallback>
            <h1>Loading posts...</h1>
        </template>
    </Suspense>
</template>
