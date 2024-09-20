<template>
    <section>
        <div class="columns">
            <div class="column is-8 is-offset-2">
                <b-field grouped group-multiline style="margin-top: 10px">
                    <b-select v-model="defaultSortDirection">
                        <option value="asc">Default sort direction: ASC</option>
                        <option value="desc">
                            Default sort direction: DESC
                        </option>
                    </b-select>
                    <b-select v-model="perPage" :disabled="!isPaginated">
                        <option value="5">5 per page</option>
                        <option value="10">10 per page</option>
                        <option value="15">15 per page</option>
                        <option value="20">20 per page</option>
                    </b-select>
                    <div class="control">
                        <button
                            class="button"
                            @click="currentPage = 1"
                            :disabled="!isPaginated"
                        >
                            Set page to 1
                        </button>
                    </div>
                    <div class="control is-flex">
                        <b-switch v-model="isPaginated">Paginated</b-switch>
                    </div>

                    <div class="control is-flex">
                        <!--
 <div class="field">
              <div class="control">
                <label class="label">Filter by Email </label>

                <input class="input has-background-dark has-text-light" type="email" placeholder="User email" v-model="filters.useremail" />
              </div>
            </div>
          -->
                    </div>
                </b-field>
                <b-table
                    :data="populateusers"
                    :loading="loading"
                    bordered
                    striped
                    narrowed
                    hoverable
                    hasMobileCards
                    is-fullwidth
                    :paginated="isPaginated"
                    :per-page="perPage"
                    :current-page.sync="currentPage"
                    :pagination-simple="isPaginationSimple"
                    :default-sort-direction="defaultSortDirection"
                >
                    <template slot-scope="props">
                        <b-table-column
                            field="actions"
                            label="Actions"
                            width="30"
                        >
                            <a data-target="#modalform">
                                <span
                                    class="icon"
                                    @click="showeditmodal(props.row.id)"
                                >
                                    <Pencil class="has-text-dark"/>
                                </span>
                                <span
                                    class="icon"
                                    @click="
                                        confirmdelete(
                                            props.row.id,
                                            props.row.email
                                        )
                                    "
                                >
                                    <TrashCan class="has-text-dark"/>
                                </span>
                            </a>
                            <a data-target="#modalform">
                                <i
                                    class="fa fa-trash"
                                    style="margin-left: 5px"
                                ></i>
                            </a>
                        </b-table-column>
                        <b-table-column
                            field="email"
                            label="Email"
                            sortable
                            width="60"
                        >
                            {{ props.row.email }}
                            <span
                                class="tag is-danger is-flex is-small"
                                style="margin: 3px"
                                v-if="props.row.password_token"
                            >
                                User need to set the password</span
                            >
                        </b-table-column>

                        <b-table-column field="study" label="Studies">
                            <span
                                class="tag is-success is-flex"
                                style="margin: 3px"
                                v-for="(study, sindex) in props.row.studies"
                                :key="sindex"
                            >{{ study.name.substring(0, 30) }} -
                                <span
                                    class="tag is-warning is-flex is-size-7"
                                    style="margin: 3px"
                                    v-for="(p, pindex) in study.permissions"
                                    :key="pindex"
                                >{{
                                        fromidtoname.permissionsNames[p]
                                    }}</span
                                ></span
                            >
                        </b-table-column>

                        <b-table-column
                            field="date"
                            label="Last login date"
                            sortable
                            centered
                        >
                            {{
                                props.row.last_login_date
                                    ? new Date(
                                        props.row.last_login_date
                                    ).toLocaleDateString() +
                                    " " +
                                    new Date(
                                        props.row.last_login_date
                                    ).toLocaleString()
                                    : "Never logged in"
                            }}
                        </b-table-column>
                    </template>

                    <template slot="empty">
                        <section class="section" style="margin-top: 10px">
                            <div
                                class="content has-text-grey has-text-centered"
                            >
                                <p>
                                    <b-icon icon="emoticon-sad" size="is-large">
                                    </b-icon>
                                </p>
                                <p>No users to show.</p>
                            </div>
                        </section>
                    </template>
                </b-table>
                <div class="column is-4 is-offset-4">
                    <b-notification
                        class="is-centered"
                        auto-close
                        type="is-success"
                        :active.sync="messageActive"
                    >
                        {{ message }}
                    </b-notification>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
import TrashCan from "vue-material-design-icons/TrashCan.vue";
import Pencil from "vue-material-design-icons/Pencil.vue";

export default {
    props: ["users"],
    components: {
        TrashCan,
        Pencil,
    },
    mounted() {},
    data() {
        return {
            errors: [],
            message: "",
            messageActive: false,
            loading: false,
            isPaginated: true,
            isPaginationSimple: false,
            defaultSortDirection: "asc",
            currentPage: 1,
            perPage: 5,
            filters: {
                useremail: "",
            },
        };
    },
    computed: {
        laterpopulateusers() {
            const email = this.filters.email.toLowerCase().trim();

            for (let i = 0; i < this.populateusers.length; i++) {
                this.users[i].studies = this.users[i].studies.reduce((acc, study) => {
                    const found = acc.find(v => v.id === study.id);
                    if (found) {
                        found.permissions.push(study.pivot.permission_id);
                    } else {
                        acc.push({
                            id: study.id,
                            name: study.name,
                            permissions: [study.pivot.permission_id],
                        });
                    }
                    return acc;
                }, []);
            }

            if (!email) return this.users;

            return this.users.filter(item => item.email.toLowerCase().includes(email));
        }
    },
    created() {
        this.populateusers = [];
        this.populatewithuser();
    },
    watch: {},
    methods: {
        getinputdata() {
            window.axios
                .post("getinputdata")
                .then((response) => {
                    this.inputs.userroles = response.data.allroles;
                    this.inputs.studies = response.data.relatedstudies;
                    this.userroleselected = [0];
                })
                .catch((error) => {});
        },
        showmodalparent() {
            this.$emit("showmodalparent");
        },
        saveuser() {
            if (this.validate()) {
                let user = {
                    email: this.email,
                    emailexist: this.emailexist,
                    roles: this.userroleselected,
                    studies: this.studiesselected,
                    permissions: this.permissions
                };

                window.axios
                    .post("users", user)
                    .then((response) => {
                        this.message = response.data;
                        this.emptyform();
                    })
                    .catch((error) => {
                        this.errors = error.data.errors;
                    });
            }
        },
        confirmdelete(id, email) {
            this.$dialog.confirm({
                title: "Confirm Delete",
                message: `Are you sure to delete user ` + email,
                cancelText: "Cancel",
                confirmText: "Delete",
                type: "is-danger",
                onConfirm: () => this.deleteuser(id),
            });
        },
        deleteuser(id) {
            this.loading = true;
            this.message = "";
            let self = this;
            axios.delete("deleteuser/" + id, { data: id }).then((response) => {
                setTimeout(function () {
                    self.populatewithuser();
                    self.loading = false;
                    self.message = "User deleted";
                }, 500);
            });
        },
        emptyform() {
            this.email = "";
            this.emailexistmessage = "";
            this.userroleselected = [];
            this.studiesselected = [];
            this.permissions.onlyconsult = false;
            this.permissions.cancreateresearcher = false;
            this.permissions.cancreatestudy = false;
        },
        populatewithuser() {
            this.loading = true;

            window.axios
                .post("apiallusers")
                .then((response) => {
                    this.populateusers = response.data;

                    for (let i = 0; i < this.populateusers.length; i++) {
                        this.populateusers[i].studies = this.populateusers[i].studies.reduce((acc, study) => {
                            const found = acc.find(v => v.id === study.id);
                            if (found) {
                                found.permissions.push(study.pivot.permission_id);
                            } else {
                                acc.push({
                                    id: study.id,
                                    name: study.name,
                                    permissions: [study.pivot.permission_id],
                                });
                            }
                            return acc;
                        }, []);
                    }

                    this.loading = false;
                })
                .catch((error) => {
                    this.errors = error.data;
                    this.loading = false;
                });
        },
        showeditmodal(id) {
            this.$emit("editusermodal", id);
        },
        realodtable() {
            this.loading = true;

            window.axios.post("apiallusers").then((response) => {
                this.populateusers = response.data;

                for (let i = 0; i < this.populateusers.length; i++) {
                    this.populateusers[i].studies = this.populateusers[i].studies.reduce((acc, study) => {
                        const found = acc.find(v => v.id === study.id);
                        if (found) {
                            found.permissions.push(study.pivot.permission_id);
                        } else {
                            acc.push({
                                id: study.id,
                                name: study.name,
                                permissions: [study.pivot.permission_id],
                            });
                        }
                        return acc;
                    }, []);
                }

                this.loading = false;
            });
        },
    },
};
</script>

