<template>
    <v-container fluid class="pa-6">
        <v-card width="1280" height="600" color="primary">
            <v-card-title> Desktop </v-card-title>
            <v-card-text class="w-100 h-100 bg-brown-lighten-5">
                <v-row>
                    <v-col cols="4" :class="bg_light">
                        <div class="default-desktop-title">
                            {{ heading }}
                        </div>
                        <div class="default-desktop-subtitle">
                            {{ subtitle }}
                        </div>
                        <div class="default-desktop-content">
                            {{ content }}
                        </div>
                        <div class="default-desktop-subcontent mt-2">
                            {{ subcontent }}
                        </div>
                    </v-col>

                    <v-col cols="4" :class="bg_dark">
                        <div class="default-desktop-title">
                            {{ heading }}
                        </div>
                        <div class="default-desktop-subtitle">
                            {{ subtitle }}
                        </div>
                        <div class="default-desktop-content">
                            {{ content }}
                        </div>
                        <div class="default-desktop-subcontent mt-2">
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
                        <div class="default-handy-title">
                            {{ heading }}
                        </div>
                        <div class="default-handy-subtitle">
                            {{ subtitle }}
                        </div>
                        <div class="default-handy-content">
                            {{ content }}
                        </div>
                        <div class="default-handy-subcontent mt-2">
                            {{ subcontent }}
                        </div>
                    </v-col>

                    <v-col cols="12" :class="bg_dark">
                        <div class="default-handy-title">
                            {{ heading }}
                        </div>
                        <div class="default-handy-subtitle">
                            {{ subtitle }}
                        </div>
                        <div class="default-handy-content">
                            {{ content }}
                        </div>
                        <div class="default-handy-subcontent mt-2">
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
            heading: "Grundlagen der absoluten Netzwerksicherheit",
            subtitle:
                "Diese Informationen sind ganz besonders wichtig für Ihre Gesundheit",
            content:
                "Die Netzwerksicherheit umfasst technische und organisatorische Maßnahmen, um Daten und Systeme vor unbefugtem Zugriff und Angriffen zu schützen. Dazu gehören Firewalls, Verschlüsselung und Intrusion-Detection-Systeme. Im IT-Unterricht analysieren wir typische Angriffe und konfigurieren Firewall-Regeln, um den Datenverkehr zu kontrollieren und die Vertraulichkeit sowie Integrität von Informationen zu gewährleisten. So lernen wir, Sicherheitskonzepte praktisch anzuwenden und Risiken zu minimieren.",
            subcontent:
                "*) Bitte beachten Sie unbedingt: Die Preise verstehen sich pro Person und Nacht.",

            bg_light: "bg-brown-lighten-5",
            bg_dark: "bg-brown-darken-4",
        };
    },
};
</script>
