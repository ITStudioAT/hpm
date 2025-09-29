<template>
    <v-card
        elevation="1"
        rounded="1"
        width="150"
        height="40"
        :color="color ? color : is_active ? 'active_folder' : is_path ? 'folder_path' : 'folder'"
        :variant="variant ? variant : 'flat'"
        class="d-flex flex-column align-center justify-center">
        <div
            class="text-caption font-weight-medium px-1 text-center"
            style="width: 100%"
            :class="is_active || is_path ? 'text-primary' : ''"
            v-if="title">
            {{ shortenText(title) }}
        </div>

        <v-icon
            size="x-small"
            :icon="icon ? icon : is_active || is_path ? 'mdi-folder-open-outline' : 'mdi-folder'"
            :color="is_active || is_path ? 'primary' : ''" />
    </v-card>
</template>

<script>
export default {
    props: ['title', 'color', 'is_active', 'is_path', 'icon', 'variant', 'delete_count'],

    data() {
        return {}
    },

    methods: {
        shortenText(text) {
            if (text.length > 20) text = '…' + text.slice(-20)
            return text
        },
    },
}
</script>
