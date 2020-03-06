<template>
    <div id="app">

        <transition name="t_navbar">
        <b-navbar v-if="navbarExists" fixed="top" v-bind:style="{ visibility: navbarVisibility }" id="nav" toggleable="md" class="navbar-dark" >
            <b-navbar-toggle target="nav_collapse"></b-navbar-toggle>

            <b-navbar-brand href="#">
                <img src="@/assets/img/logo.png" atl="GTC Logo" height="30">
            </b-navbar-brand>

            <b-collapse is-nav id="nav_collapse">
                <b-navbar-nav class="ml-auto">
                    <b-nav-item to="/">Home</b-nav-item>
                    <b-nav-item to="/about">About</b-nav-item>
                    <b-nav-item to="/test">Test</b-nav-item>
                    <b-nav-item v-b-modal.modal-login>Login</b-nav-item>
                    <b-nav-item to="/contact">Contact</b-nav-item>
                </b-navbar-nav>
                <b-nav-form @submit="updateSearch">
                    <b-form-input v-model="queryString" size="sm" class="mr-sm-2" type="text" placeholder="Search"/>
                    <b-button size="sm" class="my-2 my-sm-0" type="submit">Search</b-button>
                </b-nav-form>
            </b-collapse>
        </b-navbar>
        </transition>

        <Search @close_search="closeSearch" :queryString="queryString" v-if="isSearchVisible" />

        <router-view />

        <Footer v-if="!isPreviewVisible" msg="foo"/>

        <!-- login modal -->
        <b-modal id="modal-login"
                ref="loginModal"
                size="lg"
                centered
                :hide-header=true
                :hide-footer=true
                header-bg-variant="light"
                header-text-variant="dark"
                body-bg-variant="light"
                body-text-variant="dark"
                footer-bg-variant="light"
                footer-text-variant="dark">
            <LoginModal @close_modal="closeModal" />
        </b-modal>

    </div>
</template>

<script>
// @ is an alias to /src
import Footer from '@/components/Footer.vue';
import Search from '@/components/Search.vue';
import LoginModal from '@/components/LoginModal.vue';
import EventBus from '@/main.js';

export default {
    name: 'app',
    computed: {
        navbarVisibility() {
            return this.isPreviewVisible ? 'hidden' : 'visible'; // For preview
        },
    },
    data() {
        return {
            navbarExists: true, // navbar scroll
            lastScrollY: 0, // navbar scroll
            isPreviewVisible: false,
            isSearchVisible : false,
            queryString: '',
        }
    },
    methods: {
        closeModal() {
            this.$refs.loginModal.hide()
        },
        updateSearch(e) {
            e.preventDefault();
            this.$store.dispatch('getSearch',this.queryString, 10);
            this.isSearchVisible = true;
        },
        closeSearch() {
            this.isSearchVisible = false;
        },
        openPreview(e) {
            this.isPreviewVisible = true;
        },
        closePreview(e) {
            this.isPreviewVisible = false;
        },
        handleScroll (e) {
            // %TODO: add a timer so this doesn't fire as often
            this.navbarExists = ( window.scrollY > this.lastScrollY ) ? false : true; // scrolled down : scrolled up
            this.lastScrollY = window.scrollY;
        }
    },
    created() {
        EventBus.$on( 'open-preview', e => this.openPreview(e) );
        EventBus.$on( 'close-preview', e => this.closePreview(e) );
    },
    mounted () {
        window.addEventListener('load', () => {
            // Delay these until page load, otherwise lastScrollY get sets to 0 even if page is positioned at bottom on browser reload
            window.addEventListener('scroll', this.handleScroll);
            this.lastScrollY = window.scrollY;
        })
    },
    components: {
        LoginModal,
        Search,
        Footer
    }
}
</script>

<style lang="scss">
@import '~bootstrap/scss/_functions';
@import '~bootstrap/scss/_variables';
@import '~bootstrap/scss/mixins/_breakpoints';
@import url('https://fonts.googleapis.com/css?family=Roboto:500%7CLato:900%2C400%7CRoboto+Condensed:400');

html, body {
    background: #000;
    color: #fff;
    font-family: Lato,sans-serif;
}
h1, h2, h3, h4, h5, h6 {
    letter-spacing: .2em;
    font-weight: 300;
    line-height: 1.25em;
    text-transform: uppercase;
}

// form validation errors
.tag-verror {
    color: red; 
}
.text-hidden {
    position: absolute;
    display: block;
    overflow: hidden;
    width: 0;
    height: 0;
    color: transparent;
}

.condensed {
    font-family: Roboto Condensed;
}

#app {
    //position: relative;
}
nav#nav {
    z-index: 1000;
    background-color: #000;
    padding: 1.2em 1.8em;
    /*
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    */
}
.t_navbar {
  opacity: 1;
}
.t_navbar-enter-active {
  transition: all 0.5s;
}
.t_navbar-leave-active {
  transition: all 0.5s;
}
.t_navbar-enter, 
.t_navbar-leave-to {
  opacity: 0;
  transform: translateY(-3em);
}
nav#nav li > a {
    text-transform: uppercase;
    color: #cacaca;
}
nav#nav li a {
    font-weight: bold;
    &.router-link-exact-active {
        color: #42b983;
    }
}

@include media-breakpoint-up(md) {
    nav#nav li {
        margin-left: 1.5em;
    }
    nav#nav li:first-child {
        margin-left: 0;
    }
}

.cat-icon {
    padding: 0.8em;
    background-color: #333;
    border-radius: 0.5em; // 10px;
    cursor: pointer;
    transition: all 1s cubic-bezier(0.165, 0.84, 0.44, 1);
}
.cat-icon:hover {
    transform: translate3d(-3px, -3px, 100px);
    background: red;
    box-shadow: 0 0 30px rgba(0, 0, 0, .8);
}

/*
.debug-border {
    //border: solid 5px yellow; // debug
}
*/

</style>
