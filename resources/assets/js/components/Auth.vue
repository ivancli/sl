<template>
    <div>
        <div class="login-box m-b-0 m-t-50">
            <div class="login-logo">
                <a href="/">
                    <img src="../../images/logos/logo_transparent_white_text.png" alt="SpotLite Logo" width="360">
                </a>
            </div>
        </div>
        <div class="pre-box-body">
            <slot name="pre-box-body"></slot>
        </div>
        <div>
            <slot name="box-body"></slot>
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