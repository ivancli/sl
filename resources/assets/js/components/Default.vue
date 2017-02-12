<template>
    <div class="wrapper" :class="sidebarOpened == false ? 'sidebar-mini sidebar-collapse sidebar-open' : ''">
        <slot name="default-header"></slot>
        <slot name="default-sidebar"></slot>
        <slot name="default-content"></slot>
    </div>
</template>

<script>
    import store from '../stores/default';

    export default {
        store,
        mounted() {
            console.log('Auth component mounted.')
        },
        computed: {
            sidebarOpened: function () {
                return this.$store.state.sidebarOpened;
            }
        }
    }
</script>