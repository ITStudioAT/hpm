<template>


    <v-app-bar color="primary" density="comfortable" flat scroll-behavior="hide">
        APPBAR
    </v-app-bar>


    <v-main>
        <v-container class=" h-100 d-flex flex-column align-center justify-center main">
            <div>Text im Main</div>
            <div class="heroTitle">HeroTitle</div>
            <div class="heroLead">HeroLead</div>

            <div class="first">
                <div class="heroTitle">HeroTitle bg-first</div>
                <div class="heroLead">HeroLead bg-first</div>
            </div>

            <div class="second">
                <div class="heroTitle">HeroTitle bg-second</div>
                <div class="heroLead">HeroLead bg-second</div>
            </div>

            <div class="third">
                <div class="heroTitle">HeroTitle bg-second</div>
                <div class="heroLead">HeroLead bg-second</div>
            </div>


            <!-- IMAGE -->
            <v-card height="800" class="overflow-hidden w-100 my-10">
                <v-img src="storage/images/motiv.jpg" height="100%" cover position="center calc(50% + 500px)">
                    <!-- Overlay -->
                    <div class="d-flex flex-column justify-end align-start w-100 h-100 pa-4"
                        style="background: linear-gradient(180deg, rgba(0,0,0,0) 50%, rgba(0,0,0,.55) 100%);">
                        <div class="text-white text-h5 font-weight-bold">TEXT ÜBER DEM IMAGE</div>
                        <div class="mt-2">
                            <v-btn color="primary" variant="flat" class="mr-2" href="/dein-link">Zum Link</v-btn>
                            <v-btn color="white" variant="outlined" @click="() => { }">Aktion</v-btn>
                        </div>
                    </div>
                </v-img>
            </v-card>

            <div v-for="i in 10">
                <div style="width: 600px;" class="mt-10">
                    Lorem ipsum dolor, sit amet consectetur adipisicing elit. Ea alias sed aut. Labore, odio,
                    praesentium
                    delectus tenetur illum a molestiae id deleniti magni dicta tempore illo aut sapiente nobis
                    commodi
                    accusantium architecto aspernatur debitis asperiores voluptates perspiciatis blanditiis
                    excepturi
                    ab?
                    Consequuntur, quae debitis recusandae, earum voluptas ea nobis quam unde corrupti quasi porro
                    sunt
                    dolorum nisi optio non sed deleniti hic beatae ex blanditiis molestiae adipisci? Tempore
                    expedita
                    ipsam
                    sint aperiam accusamus, eos fugiat nihil facilis quis quam ratione optio ut nobis totam
                    explicabo,
                    quo
                    voluptate reiciendis placeat? Porro esse aliquam facilis error vel maiores doloremque tenetur
                    cum
                    labore
                    aperiam.
                </div>
            </div>




        </v-container>
    </v-main>
    <!--
        <v-footer color="surface-light" name="footer" app>
            Footer
        </v-footer>
        -->

</template>

<script>
import { mapWritableState } from "pinia";
import { useHomepageStore } from "@/stores/homepage/HomepageStore";

export default {
    async beforeMount() {
        this.homepageStore = useHomepageStore();
    },

    data() {
        return {
            homepageStore: null,
        };
    },

    computed: {
        // Kept for debugging; typography comes from CSS classes (.heroTitle, .content, ...)
        ...mapWritableState(useHomepageStore, ["homepage"]),
    },
};
</script>
