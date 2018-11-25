<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/9.13.1/styles/monokai.min.css" integrity="sha256-f1pe1glzqtZBNSOEV8RzLjSURph11H7Ieum3EVhASKw=" crossorigin="anonymous" />
    <link rel="stylesheet" href="/css/bootstrap-treeview.min.css">
    <title>Document</title>
</head>
<body>


<h1>Beavor</h1>
<div class="container-fluid">
    <div class="row">
        <div class="col col-sm-3">
                <textarea name="json" id="json" cols="30" rows="10" class="container-fluid" style="height: 80vh">
                    {!! '{
  "id": 1,
  "name": "cheri",
  "growth_time": 3,
  "max_harvest": 5,
  "natural_gift_power": 60,
  "size": 20,
  "smoothness": 25,
  "soil_dryness": 15,
  "firmness": {
    "name": "soft",
    "url": "https://pokeapi.co/api/v2/berry-firmness/2/"
  },
  "flavors": [
    {
      "potency": 10,
      "flavor": {
        "name": "spicy",
        "url": "https://pokeapi.co/api/v2/berry-flavor/1/"
      }
    }
  ],
  "item": {
    "name": "cheri-berry",
    "url": "https://pokeapi.co/api/v2/item/126/"
  },
  "natural_gift_type": {
    "name": "fire",
    "url": "https://pokeapi.co/api/v2/type/10/"
  }
}' !!}
                </textarea>
            <button id="OK">OK</button>
        </div>
        <div class="col">
            <div class="row">
                <div class="col col-sm-4">

                    <div id="tree"></div>
                </div>
                <div class="col col-sm-8">
                        <pre><code id="code" class="php hljs">

                            </code></pre>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
        crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
        crossorigin="anonymous"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.13.1/highlight.min.js"></script>
<script src="js/bootstrap-treeview.min.js"></script>
</body>
<script>

    var minify = function (json) {

        var tokenizer = /"|(\/\*)|(\*\/)|(\/\/)|\n|\r|\[|]/g,
            in_string = false,
            in_multiline_comment = false,
            in_singleline_comment = false,
            tmp, tmp2, new_str = [], ns = 0, from = 0, lc, rc
        ;

        tokenizer.lastIndex = 0;

        while (tmp = tokenizer.exec(json)) {
            lc = RegExp.leftContext;
            rc = RegExp.rightContext;
            if (!in_multiline_comment && !in_singleline_comment) {
                tmp2 = lc.substring(from);
                if (!in_string) {
                    tmp2 = tmp2.replace(/(\n|\r|\s)*/g, "");
                }
                new_str[ns++] = tmp2;
            }
            from = tokenizer.lastIndex;

            if (tmp[0] === "\"" && !in_multiline_comment && !in_singleline_comment) {
                tmp2 = lc.match(/(\\)*$/);
                if (!in_string || !tmp2 || (tmp2[0].length % 2) === 0) {    // start of string with ", or unescaped " character found to end string
                    in_string = !in_string;
                }
                from--; // include " character in next catch
                rc = json.substring(from);
            }
            else if (tmp[0] === "/*" && !in_string && !in_multiline_comment && !in_singleline_comment) {
                in_multiline_comment = true;
            }
            else if (tmp[0] === "*/" && !in_string && in_multiline_comment && !in_singleline_comment) {
                in_multiline_comment = false;
            }
            else if (tmp[0] === "//" && !in_string && !in_multiline_comment && !in_singleline_comment) {
                in_singleline_comment = true;
            }
            else if ((tmp[0] === "\n" || tmp[0] === "\r") && !in_string && !in_multiline_comment && in_singleline_comment) {
                in_singleline_comment = false;
            }
            else if (!in_multiline_comment && !in_singleline_comment && !(/\n|\r|\s/.test(tmp[0]))) {
                new_str[ns++] = tmp[0];
            }
        }
        new_str[ns++] = rc;
        return new_str.join("");
    };

    JSON.minify = minify;
    hljs.initHighlightingOnLoad();

    var treeview = $('#tree').treeview({
        data: [
            {
                text: "Parent 1",
                nodes: [
                    {
                        text: "Child 1",
                        nodes: [
                            {
                                text: "Grandchild 1"
                            },
                            {
                                text: "Grandchild 2"
                            }
                        ]
                    },
                    {
                        text: "Child 2"
                    }
                ]
            },
            {
                text: "Parent 2"
            },
            {
                text: "Parent 3"
            },
            {
                text: "Parent 4"
            },
            {
                text: "Parent 5"
            }
        ]
    });
    $('#OK').click(function () {
        $.ajax({
            url: "/beavor",
            type: "get",
            data: {
                json: JSON.minify($("#json").val())
            }
        })
            .then(function (classes) {
                $('#tree').treeview({
                    data: classes
                });
                treeview.on('nodeSelected', function (event, data) {
                    $('#code').text(data.content);
                    $('code').each(function(i, block) {
                        hljs.highlightBlock(block);
                    });
                });
            });
    });
</script>
</html>