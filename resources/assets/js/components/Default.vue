<template>
    <div class="wrapper" :class="sidebarOpened == false ? 'sidebar-mini sidebar-collapse sidebar-open' : ''">
        <slot name="default-header"></slot>
        <slot name="default-sidebar"></slot>
        <slot name="default-content"></slot>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Auth component mounted.')
        },
        computed: {
            sidebarOpened: function () {
                return this.$store.getters.sidebarOpened;
            }
        }
    }
</script>