<template>
    <v-container fluid class="pa-6">
        <v-card width="1280" height="600" color="primary">
            <v-card-title> Desktop </v-card-title>
            <v-card-text class="w-100 h-100 bg-brown-lighten-5">
                <v-row>
                    <v-col cols="4" :class="bg_light">
                        <div class="manual-desktop-title">
                            {{ heading }}
                        </div>
                        <div class="manual-desktop-subtitle">
                            {{ subtitle }}
                        </div>
                        <div class="manual-desktop-content">
                            {{ content }}
                        </div>
                        <div class="manual-desktop-subcontent mt-2">
                            {{ subcontent }}
                        </div>
                    </v-col>

                    <v-col cols="4" :class="bg_dark">
                        <div class="manual-desktop-title">
                            {{ heading }}
                        </div>
                        <div class="manual-desktop-subtitle">
                            {{ subtitle }}
                        </div>
                        <div class="manual-desktop-content">
                            {{ content }}
                        </div>
                        <div class="manual-desktop-subcontent mt-2">
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
                        <div class="manual-handy-title">
                            {{ heading }}
                        </div>
                        <div class="manual-handy-subtitle">
                            {{ subtitle }}
                        </div>
                        <div class="manual-handy-content">
                            {{ content }}
                        </div>
                        <div class="manual-handy-subcontent mt-2">
                            {{ subcontent }}
                        </div>
                    </v-col>

                    <v-col cols="12" :class="bg_dark">
                        <div class="manual-handy-title">
                            {{ heading }}
                        </div>
                        <div class="manual-handy-subtitle">
                            {{ subtitle }}
                        </div>
                        <div class="manual-handy-content">
                            {{ content }}
                        </div>
                        <div class="manual-handy-subcontent mt-2">
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
            heading: "Grundlagen der Netzwerksicherheit",
            subtitle:
                "Diese Informationen sind ganz besonders wichtig für Ihre Gesundheit",

            content:
                "Die Netzwerksicherheit umfasst technische und organisatorische Maßnahmen, um Daten und Systeme vor unbefugtem Zugriff und Angriffen zu schützen. Dazu gehören Firewalls, Verschlüsselung und Intrusion-Detection-Systeme. Im IT-Unterricht analysieren wir typische Angriffe und konfigurieren Firewall-Regeln, um den Datenverkehr zu kontrollieren und die Vertraulichkeit sowie Integrität von Informationen zu gewährleisten. So lernen wir, Sicherheitskonzepte praktisch anzuwenden und Risiken zu minimieren.",
            subcontent:
                "*) Bitte beachten Sie unbedingt: Die Preise verstehen sich pro Person und Nacht.",
            bg_light: "bg-brown-lighten-5",
            bg_light: "bg-brown-lighten-5",
            bg_dark: "bg-brown-darken-4",
        };
    },
};
</script>

<style scoped>
.source-sans-300 {
    font-family: "SourceSans", sans-serif;
    font-weight: 300;
}
.source-sans-500 {
    font-family: "SourceSans", sans-serif;
    font-weight: 500;
}

.noto-sans-300 {
    font-family: "NotoSans", sans-serif;
    font-weight: 300;
}
.noto-sans-500 {
    font-family: "NotoSans", sans-serif;
    font-weight: 500;
}

.ibm-plex-300 {
    font-family: "IbmPlex", sans-serif;
    font-weight: 300;
}
.ibm-plex-500 {
    font-family: "IbmPlex", sans-serif;
    font-weight: 500;
}

.ibm-plex-600 {
    font-family: "IbmPlex", sans-serif;
    font-weight: 600;
}
.cinzel-600 {
    font-family: "Cinzel", serif;
    font-weight: 600;
}
</style>
