<template>
    <div>
        <div class="login-box m-b-0 m-t-50">
            <div class="login-logo">
                <a href="/">
                    <img src="/images/logo_transparent_white_text.png" alt="SpotLite Logo" width="360">
                </a>
            </div>
        </div>
        <div class="pre-box-body">
            <slot name="pre-box-body"></slot>
        </div>
        <div class="login-box m-t-0">
            <div class="login-box-body">
                <slot name="login-box-body">Login, Register or Forgot Password</slot>
            </div>
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