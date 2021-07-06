<template>

    <div class="container py-5 mt-2">

        <div v-if="showAlertPrimary" class="alert alert-primary">
            v-if Alert
        </div>
        <div v-show="showAlertSecondary" class="alert alert-secondary">
            v-show Alert
        </div>

        <div>
            <button v-on:click="showAlertPrimary = !showAlertPrimary" class="btn btn-primary">Toggle Message (v-if)</button>
            <button v-on:click="showAlertSecondary = !showAlertSecondary" class="btn btn-secondary">Toggle Message (v-show)</button>
        </div>

        <h1>Vote Your Favorite Frameworks</h1>
        <ul class="list-group mb-3">
            <li v-for="framework in favoriteFrameworks" class="list-group-item d-flex justify-content-between align-items-center">
                {{ framework.name }}
                <button v-on:click="upvote(framework)" class="btn btn-outline-primary">
                    Upvote
                    <span class="badge badge-primary badge-pill">{{ framework.votes }}</span>
                </button>
            </li>
        </ul>

        <div class="form-group">
            <input v-on:keyup.enter="handleSubmit" v-on:keyup.esc="clearText" v-model="framework" type="text" class="form-control" placeholder="Enter Your another favorite Framework">
        </div>

        <h1 class="display-1 p-5" :style="[style1, style2, style3, style4]">Style me</h1>

        <div class="form-row">
            <div class="form-group col-md-2">
                <label>Background</label>
                <select v-model="style1.backgroundColor" class="form-control">
                    <option>Background</option>
                    <option value="white">White</option>
                    <option value="red">Red</option>
                    <option value="green">Green</option>
                    <option value="blue">Blue</option>
                </select>
            </div>
            <div class="form-group col-md-2">
                <label>Text Color</label>
                <select v-model="style2.color" class="form-control">
                    <option>Color</option>
                    <option value="black">Black</option>
                    <option value="white">White</option>
                    <option value="yellow">Yellow</option>
                </select>
            </div>
            <div class="form-group col-md-2">
                <label>Border</label>
                <select v-model="style3.border" class="form-control">
                    <option>Border</option>
                    <option value="2px dashed red">2px red</option>
                    <option value="4px dashed green">4px green</option>
                    <option value="8px dashed blue">8px blue</option>
                </select>
            </div>
            <div class="form-group col-md-2">
                <label>Text Transform</label>
                <select v-model="style4.textTransform" class="form-control">
                    <option>Transform</option>
                    <option value="capitalize">Capitalize</option>
                    <option value="uppercase">Uppercase</option>
                    <option value="lowercase">Lowercase</option>
                </select>
            </div>
        </div>

        <h2>Top 10 Frameworks</h2>
        <table class="table table-bordered table-striped">
            <thead class="thead-dark">
            <tr>
                <th>Framework</th>
                <th @click="toggle" :class="['sort-control', sortType]">Score</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="framework of topTenFrameworksSorted">
                <td>{{ framework.name }}</td>
                <td>{{ framework.score }}</td>
            </tr>
            </tbody>
        </table>

        <form class="form-inline">
            <select v-model="title" class="form-control mr-2">
                <option value="Mr.">Mr.</option>
                <option value="Ms.">Ms.</option>
            </select>
            <input type="text" v-model="name" placeholder="Name" class="form-control mr-2">
            <input type="text" v-model="email" placeholder="Email" class="form-control">
        </form>
        <hr>
        <div class="alert alert-info">
            <p>Uppercase name: {{ upperName() }}</p>
            <p>Lowercase name: {{ lowerName }}</p>
        </div>

    </div>

</template>

<script>
    export default {
        mounted() {
            this.title = "Mr.",
            console.log('Component mounted.')
        },
        data: function () {
            return {
                topTenFrameworks: [
                    { name: "Spring", score: 88 },
                    { name: "ASP NET", score: 100 },
                    { name: "Ruby on Rails", score: 95 },
                    { name: "Express", score: 87 },
                    { name: "AngularJS", score: 96 },
                    { name: "Django", score: 91 },
                    { name: "Angular", score: 89 },
                    { name: "Laravel", score: 89 },
                    { name: "React", score: 93 },
                    { name: "Vue.js", score: 89}
                ],
                name: 'John Doe',
                email: 'johndoe@test.com',
                order: 1,
                showAlertPrimary: true,
                showAlertSecondary: true,
                favoriteFrameworks: [
                    { name: "Angular", votes: 0 },
                    { name: "React.js", votes: 3 },
                    { name: "Vue.js", votes: 7 },
                ],
                framework: '',
                style1: {
                    backgroundColor: 'white'
                },
                style2: {
                    color: 'black'
                },
                style3: {
                    border: '2px dashed red'
                },
                style4: {
                    textTransform: 'lowercase'
                },
                alertType: 'alert-primary'
            }
        },
        computed: {

            lowerName () {
                console.log('computed lowerName ()');
                return (this.usingMyConstant() + " " + this.name).toLowerCase();
            },
            topTenFrameworksSorted () {
                return this.topTenFrameworks.sort((a, b) => (a.score - b.score) * this.order);
            },

            sortType () {
                return this.order === 1 ? 'ascending' : 'descending';
            }
        },
        methods: {
            usingMyConstant () {
                return this.title;
            },
            upperName () {
                console.log('upperName ()');
                return (this.usingMyConstant() + " " + this.name).toUpperCase();
            },
            toggle () {
                return this.order *= -1;
            },
            upvote (framework) {
                framework.votes++
            },
            handleSubmit () {
                if (this.framework.trim()) {
                    this.favoriteFrameworks.push({
                        name: this.framework,
                        votes: 8
                    });
                    this.framework = ''
                }
            },
            clearText () {
                this.framework = ''
            }
        }
    }
</script>
