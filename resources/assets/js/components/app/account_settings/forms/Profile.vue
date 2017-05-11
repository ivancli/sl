<template>
    <div>
        <div class="form-group">
            <label for="title" class="control-label col-md-3">Title</label>
            <div class="col-md-9">
                <select class="form-control sl-form-control" id="title" v-model="title">
                    <option value="">Please select</option>
                    <option value="Miss">Miss</option>
                    <option value="Mr">Mr</option>
                    <option value="Mrs">Mrs</option>
                    <option value="Ms">Ms</option>
                </select>
            </div>
        </div>
        <div class="form-group required">
            <label for="first_name" class="control-label col-md-3">First name</label>
            <div class="col-md-9">
                <input class="form-control" type="text" id="first_name" v-model="firstName">
            </div>
        </div>
        <div class="form-group required">
            <label for="last_name" class="control-label col-md-3">Last name</label>
            <div class="col-md-9">
                <input class="form-control" type="text" id="last_name" v-model="lastName">
            </div>
        </div>
        <div class="form-group required">
            <label for="email" class="control-label col-md-3">Email</label>
            <div class="col-md-9">
                <input class="form-control" disabled="disabled" type="email" id="email" v-model="email">
            </div>
        </div>
    </div>
</template>
<script>
    import {
        SET_PROFILE
    } from '../../../../actions/action-types';

    export default{
        data(){
            return {
                title: null,
                firstName: null,
                lastName: null,
                email: null,
            }
        },
        mounted(){
            console.info('Profile.vue is mounted');
            this.initProfile();
        },
        watch: {
            title(){
                this.setProfile();
            },
            firstName(){
                this.setProfile();
            },
            lastName(){
                this.setProfile();
            },
            email(){
                this.setProfile();
            }
        },
        methods: {
            initProfile(){
                this.title = user.title;
                this.firstName = user.first_name;
                this.lastName = user.last_name;
                this.email = user.email;
            },
            setProfile(){
                this.$store.dispatch(SET_PROFILE, {
                    profile: this.setProfileData
                });
            }
        },
        computed: {
            profile(){
                return this.$store.getters.profile;
            },
            setProfileData(){
                return {
                    title: this.title,
                    first_name: this.firstName,
                    last_name: this.lastName,
                    email: this.email,
                };
            },
        }
    }
</script>
<style>

</style>