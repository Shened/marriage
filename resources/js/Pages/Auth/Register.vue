<template>
    <AuthLayout>
        <v-card width="420">
            <v-card-title class="text-h6">Register</v-card-title>

            <v-card-text>
                <v-text-field label="Name" v-model="form.name" />

                <v-text-field label="Email" type="email" v-model="form.email" />

                <v-text-field label="Password" type="password" v-model="form.password" />

                <v-text-field label="Confirm Password" type="password" v-model="form.password_confirmation" />
            </v-card-text>

            <v-card-actions>
                <v-btn color="primary" block :loading="form.processing" @click="submit">
                    Register
                </v-btn>
            </v-card-actions>
        </v-card>
    </AuthLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'
import AuthLayout from '@/Layouts/AuthLayout.vue'

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
})

const submit = () => {
    form.post(route('register'))
}
</script>
