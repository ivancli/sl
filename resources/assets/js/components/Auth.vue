<template>
    <div class="login-box">
        <div class="login-logo">
            <a href="/">
                <img src="/images/logo_transparent_white_text.png" alt="SpotLite Logo" width="360">
            </a>
        </div>
        <div class="pre-box-body">
            <slot name="pre-box-body"></slot>
        </div>
        <div class="login-box-body">
            <slot name="login-box-body">Login, Register or Forgot Password</slot>
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