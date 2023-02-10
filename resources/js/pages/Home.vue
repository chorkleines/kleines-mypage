<template>
    <App>
        <template v-slot:content>
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">Example Component</div>
                            <div class="card-body">
                                I'm an example component.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </App>
</template>

<script lang="ts">
import { reactive, defineComponent } from "vue";
import App from "@/components/App.vue";

export default defineComponent({
    components: {
        App,
    },
});
</script>
