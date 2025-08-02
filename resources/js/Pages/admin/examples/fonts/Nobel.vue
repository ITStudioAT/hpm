<template>
    <v-container fluid class="pa-6">
        <v-card width="1280" height="600" color="primary">
            <v-card-title> Desktop </v-card-title>
            <v-card-text class="w-100 h-100 bg-brown-lighten-5">
                <v-row>
                    <v-col cols="4" :class="bg_light">
                        <div class="nobel-desktop-title">
                            {{ heading }}
                        </div>
                        <div class="nobel-desktop-subtitle">
                            {{ subtitle }}
                        </div>
                        <div class="nobel-desktop-content">
                            {{ content }}
                        </div>
                        <div class="nobel-desktop-subcontent mt-2">
                            {{ subcontent }}
                        </div>
                    </v-col>

                    <v-col cols="4" :class="bg_dark">
                        <div class="nobel-desktop-title">
                            {{ heading }}
                        </div>
                        <div class="nobel-desktop-subtitle">
                            {{ subtitle }}
                        </div>
                        <div class="nobel-desktop-content">
                            {{ content }}
                        </div>
                        <div class="nobel-desktop-subcontent mt-2">
                            {{ subcontent }}
                        </div>
                    </v-col>
                </v-row>
            </v-card-text>
        </v-card>

        <v-card width="400" height="1000" color="primary" class="mt-4">
            <v-card-title> Handy </v-card-title>
            <v-card-text class="w-100 h-100 bg-brown-lighten-5">
                <v-row>
                    <v-col cols="12" :class="bg_light">
                        <div class="nobel-handy-title">
                            {{ heading }}
                        </div>
                        <div class="nobel-handy-subtitle">
                            {{ subtitle }}
                        </div>
                        <div class="nobel-handy-content">
                            {{ content }}
                        </div>
                        <div class="nobel-handy-subcontent mt-2">
                            {{ subcontent }}
                        </div>
                    </v-col>

                    <v-col cols="12" :class="bg_dark">
                        <div class="nobel-handy-title">
                            {{ heading }}
                        </div>
                        <div class="nobel-handy-subtitle">
                            {{ subtitle }}
                        </div>
                        <div class="nobel-handy-content">
                            {{ content }}
                        </div>
                        <div class="nobel-handy-subcontent mt-2">
                            {{ subcontent }}
                        </div>
                    </v-col>
                </v-row>
            </v-card-text>
        </v-card>
    </v-container>
</template>

<script>
export default {
    data() {
        return {
            heading: "Urlaub im Vitalhotel Gosau",
            subtitle:
                "Diese Informationen sind ganz besonders wichtig für Ihre Gesundheit",
            content:
                "Kühle Bäche, klare Seen und frische Bergluft – das idyllische Bergdorf Gosau am Dachstein ruft! Sichern Sie sich jetzt Ihren unvergesslichen Sommerurlaub und entdecken Sie echte Schmankerl – kulinarisch wie erlebnisreich. Unsere Wochenhighlights lassen keine Wünsche offen: Yoga im Garten, Grillabende auf der Terrasse mit atemberaubendem Bergblick, Live-Musik in der Bar und vieles mehr erwarten Sie.",
            subcontent:
                "*) Bitte beachten Sie unbedingt: Die Preise verstehen sich pro Person und Nacht.",
            bg_light: "bg-brown-lighten-5",
            bg_dark: "bg-brown-darken-4",
        };
    },
};
</script>
