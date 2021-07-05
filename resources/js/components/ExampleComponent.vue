<template>

    <div class="container py-5 mt-2">
        <h2>Top 10 Frameworks</h2>
        <table class="table table-bordered">
            <tr>
                <th>Framework</th>
                <th>Github Score</th>
                <th>Stack Overflow Score</th>
                <th>Overall Score</th>
            </tr>
            <tr v-for="framework of topTenFrameworks">
                <td>{{ framework.name }}</td>
                <td>{{ framework.github }}</td>
                <td>{{ framework.stackoverflow }}</td>
                <td>{{ framework.overall }}</td>
            </tr>
        </table>
    </div>

</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        },
        data: function () {
            return {
                topTenFrameworks: [
                    { name: "ASP.NET", github: 39, stackoverflow: 100, overall: 100 },
                    { name: "AngularJS", github: 96, stackoverflow: 97, overall: 96 },
                    { name: "Ruby on Rails", github: 92, stackoverflow: 98, overall: 95 },
                    { name: "ASP NET", github: 38, stackoverflow: 94, overall: 94 },
                    { name: "React", github: 100, stackoverflow: 87, overall: 93 },
                    { name: "Django", github: 89, stackoverflow: 94, overall: 91 },
                    { name: "Angular", github: 90, stackoverflow: 89, overall: 89 },
                    { name: "Laravel", github: 91, stackoverflow: 88, overall: 89 },
                    { name: "Spring", github: 84, stackoverflow: 92, overall: 88 },
                    { name: "Express", github: 91, stackoverflow: 83, overall: 87 }
                ]
            }
        }
    }
</script>
