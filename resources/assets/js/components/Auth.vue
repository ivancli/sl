<template>
    <div class="login-box">
        <div class="login-logo">
            <a href="/"><b>Admin</b>LTE</a>
        </div>
        <div class="login-box-body">
            <slot>
                This is fake slot
            </slot>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Auth component mounted.')
        }
    }
</script>