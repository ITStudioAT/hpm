<template>
    <!-- EDIT MENU ACTIONS -->

    <v-card flat tile color="surface" border>
        <v-card-title class="d-flex flex-row align-center justify-space-between bg-accent-2">
            <div>
                {{ active_id > 0 ? findNodeById(root, active_id).title : active_menu.name }}
            </div>
        </v-card-title>
        <v-card-subtitle v-if="active_id > 0 && findNodeById(root, active_id).url">
            {{ findNodeById(root, active_id).url }}
        </v-card-subtitle>
        <v-card-subtitle v-if="active_id > 0 && findNodeById(root, active_id).page_id">Interner Link</v-card-subtitle>
        <v-card-text class="py-4">
            <v-card tile flat class="d-flex flex-wrap ga-2" :disabled="selected_menu_action != ''">
                <!--TEST 
                        <its-menu-button title="Test" icon="mdi-test-tube" color="error" @click="doTest" />
                        -->

                <!-- Text hinzufügen -->
                <its-menu-button
                    title="Text hinzufügen"
                    icon="mdi-pencil-plus"
                    :color="selected_menu_action == 'addText' ? 'primary' : 'secondary'"
                    @click="addText(active_id)" />
                <!-- Seite hinzufügen -->
                <its-menu-button
                    title="Seite hinzufügen"
                    icon="mdi-book-plus"
                    :color="selected_menu_action == 'addPage' ? 'primary' : 'secondary'"
                    @click="addPage(active_id)" />
                <!-- Link hinzufügen -->
                <its-menu-button
                    title="Link hinzufügen"
                    icon="mdi-link-plus"
                    :color="selected_menu_action == 'addLink' ? 'primary' : 'secondary'"
                    @click="addLink(active_id)" />
                <!-- Löschen -->
                <its-menu-button
                    title="Löschen"
                    icon="mdi-delete"
                    :color="selected_menu_action == 'addURL' ? 'primary' : 'warning'"
                    @click="deleteNode(root, active_id)"
                    v-if="active_id > 0" />
            </v-card>
        </v-card-text>
        <!-- Text hinzufügen -->
        <v-card-text v-if="selected_menu_action == 'addText'">
            <v-form ref="form" @submit.prevent="doAddText(active_id, data)" v-model="is_valid">
                <v-card tile flat>
                    <v-card-title class="d-flex flex-row align-center justify-space-between bg-secondary mb-4">
                        <div>Text hinzufügen</div>
                    </v-card-title>
                    <v-card-text>
                        <v-text-field
                            autofocus
                            v-model="data.text"
                            label="Text"
                            :rules="[required(), maxLength(255)]" />
                    </v-card-text>
                    <v-card-actions class="d-flex flex-row align-center justify-space-between">
                        <v-btn flat tile variant="flat" color="warning" @click="doAbortMenuAction">Abbruch</v-btn>
                        <v-btn flat tile variant="flat" color="success" type="submit">Hinzufügen</v-btn>
                    </v-card-actions>
                </v-card>
            </v-form>
        </v-card-text>

        <!-- Link hinzufügen -->
        <v-card-text v-if="selected_menu_action == 'addLink'">
            <v-form ref="form" @submit.prevent="doAddLink(active_id, data)" v-model="is_valid">
                <v-card tile flat>
                    <v-card-title class="d-flex flex-row align-center justify-space-between bg-secondary mb-4">
                        <div>Text hinzufügen</div>
                    </v-card-title>
                    <v-card-text>
                        <v-text-field
                            autofocus
                            v-model="data.text"
                            label="Text"
                            :rules="[required(), maxLength(255)]" />
                        <v-text-field
                            v-model="data.link"
                            label="Link (http://)"
                            :rules="[required(), maxLength(255)]" />
                    </v-card-text>
                    <v-card-actions class="d-flex flex-row align-center justify-space-between">
                        <v-btn flat tile variant="flat" color="warning" @click="doAbortMenuAction">Abbruch</v-btn>
                        <v-btn flat tile variant="flat" color="success" type="submit">Hinzufügen</v-btn>
                    </v-card-actions>
                </v-card>
            </v-form>
        </v-card-text>

        <!-- Seite hinzufügen -->
        <v-card-text v-if="selected_menu_action == 'addPage'">
            <AddPage :homepage="homepage" @addPage="doAddPage" />
        </v-card-text>
    </v-card>
</template>
<script>
import { mapWritableState } from 'pinia'
import { shallowRef } from 'vue'
import { useValidationRulesSetup } from '@/helpers/rules'
import { useAdminStore } from '@/stores/admin/AdminStore'
import { useMenuStore } from '@/stores/admin/MenuStore'
import { usePageStore } from '@/stores/admin/PageStore'
import ItsMenuButton from '@/pages/components/ItsMenuButton.vue'
import AddPage from './AddPage/AddPage.vue'

export default {
    setup() {
        return useValidationRulesSetup()
    },
    props: ['active_menu', 'homepage'],
    emits: [],

    components: { ItsMenuButton, AddPage },

    async beforeMount() {
        this.adminStore = useAdminStore()
        this.menuStore = useMenuStore()
        this.pageStore = usePageStore()
        this.doOverview(this.homepage.id)
        this.items = shallowRef([...this.root.children])
    },

    unmounted() {},

    data() {
        return {
            max_id: 0,
            test_node: null,
            data: {},
            adminStore: null,
            menuStore: null,
            pageStore: null,
            is_valid: false,
        }
    },

    computed: {
        ...mapWritableState(useAdminStore, ['is_in_work']),
        ...mapWritableState(useMenuStore, ['selected_menu_action', 'active_id', 'root', 'items']),
        ...mapWritableState(usePageStore, ['pages']),
        activeIdProxy: {
            get() {
                // always return an array — if active_id is falsy, use [0]
                return [this.active_id || 0]
            },
            set(val) {
                // if val is empty or invalid, set to 0
                this.active_id = Array.isArray(val) && val.length > 0 ? val[0] : 0
            },
        },
    },

    methods: {
        async doOverview(homepage_id) {
            await this.pageStore.index(homepage_id)
            this.delete_action = 0
            this.selected_action = ''
        },
        doTest() {
            const my_id = 3
            this.test_node = this.findNodeById(this.root, my_id)
            const data = { text: 'New Node' }
            this.doAddText(my_id, data)
        },
        doAbortMenuAction() {
            this.data = {}
            this.selected_menu_action = ''
        },
        addText() {
            this.data = {}
            this.selected_menu_action = 'addText'
        },
        addLink() {
            this.data = {}
            this.selected_menu_action = 'addLink'
        },
        addPage() {
            this.data = {}
            this.selected_menu_action = 'addPage'
        },

        doAddText(active_id, data) {
            let node = this.findNodeById(this.root, active_id)
            let id_high = this.findHighestId(this.root) + 1
            if (node) {
                if (!Array.isArray(node.children)) {
                    node.children = []
                }
                node.children.push({
                    id: id_high,
                    title: data.text,
                    // children: [],
                })
            }

            // Update your reactive state
            this.items = shallowRef([...this.root.children])
            this.selected_menu_action = ''
        },

        doAddLink(active_id, data) {
            let node = this.findNodeById(this.root, active_id)
            let id_high = this.findHighestId(this.root) + 1
            if (node) {
                if (!Array.isArray(node.children)) {
                    node.children = []
                }
                node.children.push({
                    id: id_high,
                    title: data.text,
                    url: this.cleanLink(data.link),
                    // children: [],
                })
            }

            // Update your reactive state
            this.items = shallowRef([...this.root.children])
            this.selected_menu_action = ''
        },

        doAddPage(active_page_id) {
            console.log('active_id: ', this.active_id)
            console.log('active_page_id: ', active_page_id)
            let page = this.findPageById(active_page_id)

            let node = this.findNodeById(this.root, this.active_id)
            let id_high = this.findHighestId(this.root) + 1
            if (node) {
                if (!Array.isArray(node.children)) {
                    node.children = []
                }
                node.children.push({
                    id: id_high,
                    title: page.name,
                    page_id: page.id,
                    // children: [],
                })
            }

            // Update your reactive state
            this.items = shallowRef([...this.root.children])
            this.selected_menu_action = ''
        },

        findPageById(id) {
            return this.pages.find((page) => page.id === id) || null
        },

        cleanLink(input) {
            if (!input) return ''

            const trimmed = input.trim()

            // If it already starts with "<something>://", leave it as is
            if (/^[a-z][a-z0-9+\-.]*:\/\//i.test(trimmed)) {
                return trimmed
            }

            // Otherwise, prepend https://
            return 'https://' + trimmed.replace(/^\/+/, '')
        },

        findNodeById(node, id) {
            if (node.id === id) {
                return node // found it
            }

            if (node.children) {
                for (let child of node.children) {
                    const found = this.findNodeById(child, id)
                    if (found) {
                        return found
                    }
                }
            }

            return null // not found
        },
        findHighestId(node) {
            let maxId = node.id
            if (node.children) {
                for (const child of node.children) {
                    const childMax = this.findHighestId(child)
                    if (childMax > maxId) {
                        maxId = childMax
                    }
                }
            }
            return maxId
        },

        findParentByChildId(node, id) {
            if (!Array.isArray(node.children)) return null

            for (let i = 0; i < node.children.length; i++) {
                const child = node.children[i]
                if (child.id === id) {
                    return { parent: node, index: i } // found direct parent
                }
                const deeper = this.findParentByChildId(child, id)
                if (deeper) return deeper
            }
            return null
        },
        deleteNode(root, id) {
            this.active_id = 0 // reset selection to 0 (scalar)

            if (root && root.id === id) return null

            const hit = this.findParentByChildId(root, id)
            if (!hit) return null

            const { parent, index } = hit
            const [removed] = parent.children.splice(index, 1)

            // ✅ If children is now empty, remove the property
            if (Array.isArray(parent.children) && parent.children.length === 0 && parent.id != 0) {
                delete parent.children
            } else {
                // Reassign the mutated children array so Vue/Vuetify notices
                parent.children = [...parent.children]
            }

            // ✅ If the deleted node was at level 1 (parent === root)
            if (parent === this.root) {
                // EITHER: if your tree uses :items="items"
                this.items = [...this.root.children]
            }

            return removed ?? null
        },
    },
}
</script>
