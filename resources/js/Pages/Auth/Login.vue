<template>
    <AuthLayout>
        <v-card width="420">
            <v-card-title class="text-h6">Login</v-card-title>

            <v-card-text>
                <v-text-field label="Email" type="email" v-model="form.email" />

                <v-text-field label="Password" type="password" v-model="form.password" />

                <v-checkbox label="Remember me" v-model="form.remember" />
            </v-card-text>

            <v-card-actions>
                <v-btn color="primary" block :loading="form.processing" @click="submit">
                    Login
                </v-btn>
            </v-card-actions>
        </v-card>
    </AuthLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'
import AuthLayout from '@/Layouts/AuthLayout.vue'

const form = useForm({
    email: '',
    password: '',
    remember: false,
})

const submit = () => {
    form.post(route('login'))
}
</script>
