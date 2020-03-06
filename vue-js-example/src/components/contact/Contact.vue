<template>
<b-container fluid id="wrap-contact_contact" class="debug-border template-wrap">

    <!--
<div id="subwrap">
    -->
    <b-row class="justify-content-center no-gutters">
        <b-col id="form-contact" class="supercrate  OFF-my-auto col-xs-12 col-sm-12 col-md-4">
            <div class="">
                <h2>Let's<br />Connect</h2>
                <b-form @submit="submitForm" :validated="wasValidated" novalidate class="OFF-ui OFF-form"> 
                    <b-form-group id="fg-contact_name" label="Name" label-for="contact_name" >
                        <b-form-input id="contact_name" type="text" v-model="fields.name" required placeholder="Enter full name..."></b-form-input>
                        <b-form-invalid-feedback>{{ fieldErrors.name }} </b-form-invalid-feedback>
                    </b-form-group>
                    <b-form-group id="fg-contact_phone" label="Phone" label-for="contact_phone" >
                        <b-form-input id="contact_phone" type="text" v-model="fields.phone" required placeholder="Enter phone..."></b-form-input>
                        <b-form-invalid-feedback>{{ fieldErrors.phone }} </b-form-invalid-feedback>
                    </b-form-group>
                    <b-form-group id="fg-contact_message" label="Message" label-for="contact_message" >
                        <b-form-textarea id="contact_message" v-model="fields.message" rows="5" placeholder=""></b-form-textarea>
                        <b-form-invalid-feedback>{{ fieldErrors.message }} </b-form-invalid-feedback>
                    </b-form-group>
                    <b-button class="tag-clickme_to_submit_form" size=lg type="submit" variant="primary">Submit</b-button>
                </b-form>
            </div>
        </b-col>
        <b-col class="OFF-my-auto supercrate col-xs-12 col-sm-12 col-md-4">
            <div id="company-map" class="">
                <img src="./assets/img/675cb-snazzy-image.png" alt="Company Location on Map" class="OFF-img-fluid">
            </div>
        </b-col>
    </b-row>
    <!--
</div>
    -->

</b-container>
</template>

<script>
// %TODO: get rid of v-100 on this page, adjust accoringly
// BS4 Form Validation:
//   ~ https://bootstrap-vue.js.org/docs/components/form
//   ~ https://getbootstrap.com/docs/4.0/components/forms/#validation

export default {
    name: 'Contact',
    props: {
        //validated: Boolean,
        msg: String
    },
    computed: {
    },
    data () {
        return {
            fields: {
                name: '',
                phone: '',
                message: ''
            },
            fieldErrors: {
                name: undefined,
                phone: undefined,
                message: undefined
            },
            wasValidated: false
        }
    },
    methods: {
        submitForm(e) {
            e.preventDefault();
            this.fieldErrors = this.validateForm(this.fields); 
            if ( Object.keys(this.fieldErrors).length ) {
                return; // client-level form validation failed
            }
            //this.items.push(this.fields.name);  // %TODO: ajax POST

            // reset form
            this.fields.name = ''; 
            this.fields.phone = ''; 
            this.fields.message = ''; 
        },
        validateForm(fields) {
            const errors = {};
            this.wasValidated = true;
            if (!fields.name) errors.name = "Name Required"; 
            if (!fields.phone) errors.phone = "Phone Required";
            if (!fields.message) errors.message = "Message Required"; 
            if (fields.phone && !this.isValidPhone(fields.phone)) { 
                errors.phone = "Invalid Phone";
            }
            return errors;
        },
        isValidPhone(str) {
            return true; // %TODO
            //const re = /\S+@\S+\.\S+/; // regex for email format %FIXME
            //return re.test(str); 
        }
    }
}
</script>

<style lang="scss">
#page-contact form label {
    text-transform: uppercase;
    color: #5c5c5c;
}
label[for=contact_name]:after,
label[for=contact_phone]:after {
    color: #e32;
    content: ' *';
    display:inline;
}
</style>

<style scoped lang="scss">
@import '~bootstrap/scss/_functions';
@import '~bootstrap/scss/_variables';
@import '~bootstrap/scss/mixins/_breakpoints';

#form-contact {
    background-color: #fff;
    color: #000;
}
#form-contact > div {
    padding: 50px;
}
#form-contact h2 {
    margin-bottom: 25px;
}
.supercrate { 
}
#company-map {
    overflow: hidden;
}
#subwrap {
    padding: 35px 0;
}

button.tag-clickme_to_submit_form {
    background-color: red;
    border-color: red;
}

// Responsive breakpoints
@include media-breakpoint-down(xs) {
    #company-map img {
        //width: 100%;
    }
    // For some reason we need to apply this to get BS4 to treat xs like an sm (in this case at least ?)
    .col-xs-12 {
        -webkit-box-flex: 0;
        flex: 0 0 100%;
        max-width: 100%
    }
}
@include media-breakpoint-up(xs) {
    #company-map img {
        //border: solid 2px pink;
        width: 100%;
    }
}

@include media-breakpoint-up(sm) {
    #company-map img {
        //border: solid 2px orange;
    }
}

@include media-breakpoint-up(md) {
    #page-contact .template-wrap {
        height: 100vh;
    }
    .template-wrap .supercrate {
        height: 80vh;
    }
    #company-map img {
        //border: solid 2px cyan;
        width: inherit;
        height: 80vh;
    }
}

@include media-breakpoint-up(lg) {
}
</style>
