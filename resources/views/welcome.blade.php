<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
        body {
            font-family: 'Nunito', sans-serif;
        }

    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">

    <!-- Script js -->
    <script src="{{ mix('js/app.js') }}" defer></script>
</head>

<body class="w-screen h-screen">
    <header>
        <div class="flex w-full static">
            <div class="py-4 w-full border-0 shadow bg-white flex flex-row justify-between">
                <a href="{{ route('homepage') }}">
                    <div class="ml-4">
                        <div class="font-bold text-xl text-gray-700">
                            Lapen
                        </div>
                        <div class="font-thin text-xs text-gray-700">
                            Lah, kok pendek!
                        </div>
                    </div>
                </a>
                <div class="mr-4">
                    <div class="pt-3 text-gray-700">
                        <i class="bi bi-github"></i>
                        <a href="https://github.com/Ryuze">Alfian Yunianto Suseno</a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="flex flex-col overflow-auto static">
        <div class="m-auto pt-40 space-y-4">
            <p class="text-xl font-bold text-gray-700 text-center">
                Masukkan URL yang akan dipendekkan disini!
            </p>
            <form id="dataLink" action="">
                <div class="space-y-2">
                    <div>
                        <input type="url" name="url" id="url" placeholder="URL disini..."
                            class="w-full px-2 py-2 shadow border-2 border-green-500 rounded hover:border-green-600 focus:border-green-600">
                    </div>
                    <div>
                        <button type="submit"
                            class="text-center font-bold transition ease-in-out duration-150 w-full bg-green-500 rounded py-2 text-white hover:bg-green-600 hover:text-gray-100">Pendek!</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="m-auto p-6 max-w-3xl">
            <table class="table-fixed border-2 border-green-400">
                <thead>
                    <tr class="border">
                        <th class="w-1/2">Shortened URL</th>
                        <th class="w-1/2">Real URL</th>
                    </tr>
                </thead>
                <tbody id="bodyTable">
                </tbody>
            </table>
        </div>
    </div>
</body>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    document.getElementById('dataLink').addEventListener('submit', (event) => {
        event.preventDefault()
        dataPost()
    })

    window.onload = loadData()

    function dataPost() {
        const link = document.getElementById('url').value.trim()
        axios.post('/api/link', {
                real_link: link
            })
            .then(function(res) {
                let data = []
                const genLink = `http://127.0.0.1:8000/${res.data.data.gen_link}`
                const temp = localStorage.getItem('LOCAL_LINKS')
                const dataLink = dataToObject(res.data.data.gen_link, link)

                if (temp) {
                    data = JSON.parse(temp)
                }

                data.unshift(dataLink)
                localStorage.setItem('LOCAL_LINKS', JSON.stringify(data))
                cleanData()

                document.getElementById('bodyTable').insertAdjacentHTML('afterbegin',
                    `<tr class="border"><td class="p-4"><a href="${genLink}">${genLink}</a></td><td class="p-4"><a href="${link}">${link}</a></td></tr>`
                    )
            })
            .catch(function(err) {
                console.error(err)
            })
    }

    function dataToObject(genLink, realLink) {
        return {
            "gen_link": genLink,
            "real_link": realLink
        }
    }

    function cleanData() {
        document.getElementById('url').value = ""
    }

    function loadData() {
        let fetch = JSON.parse(localStorage.getItem('LOCAL_LINKS'))

        fetch.reverse().forEach(element => {
            document.getElementById('bodyTable').insertAdjacentHTML('afterbegin',
                `<tr class="border"><td class="p-4"><a href="http://127.0.0.1:8000/${element.gen_link}">http://127.0.0.1:8000/${element.gen_link}</a></td><td class="p-4"><a href="${element.real_link}">${element.real_link}</a></td></tr>`
                )
        });
    }
</script>

</html>
