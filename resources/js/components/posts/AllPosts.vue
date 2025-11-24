<script>
import { defineComponent, onMounted, ref } from 'vue';
import { all } from '@/api/posts';

export default defineComponent({
    setup: async () => {
        const posts = await all();

        return {
            posts
        }
    }
});
</script>

<template>
    <section class="flex p-4 m-4 card gap-4 card-border shadow-md" v-for="post in posts" :key="post.post_id">
        <div class="card-body">
            <h1 class="card-title">{{ post.title }}</h1>
            <aside class="card-side">by {{ post.profile.name }}</aside>
            <p>{{ post.body }}</p>
        </div>
    </section>
</template>
