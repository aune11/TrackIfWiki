##Practices for Scraping Sites with Regular Expressions

####Introduction

TrackIf’s Parsing API was built under the concept of having custom PHP scripts written for each domain that would handle the data extraction for a given type of page. For example, a product page on a company’s website would execute a different script to parse out its data than it would on that same company’s search results pages. This resulted in a heavy focus on using regular expressions to pull all of the data from a page’s source code. Because regular expressions are difficult to write well, they are prone to break more frequently than by other methods which are designed to scrape data.

Despite TrackIf moving the focus to selectors as the preferred means of data extraction, it is still occasionally necessary to parse a page using regular expressions. The target page could have poor syntax that may render in a browser, yet break PHP’s DOM parsing engine. Sometimes the target page has a lot of the same tags with few or no unique attributes, such as old websites that still use tables for their layouts, which can be a nightmare to navigate when using selectors. Or sometimes, a page may store its data in a JavaScript object and inject it into the DOM on the fly.

Whatever the case for using regular expressions, it is important to know how to write them well. This guide will go over some of the best practices for writing regular expressions for parsing HTML and why other practices are often dangerous. It is assumed that the reader has a basic knowledge of regular expressions before reading this guide. For example, if you don't know why `[\d]` is silly, then you're not ready for this guide. It is also suggested that the reader goes through [this article by Jeff Atwood](http://blog.codinghorror.com/parsing-html-the-cthulhu-way/) which explains why parsing a page in this way is very often a bad idea.

Before we jump in, it is important to remember that the purpose of regular expressions is pattern matching. Many people who try to parse pages fail to realize this when they first start out, so they write really weak patterns that will break over time.

####Ways that Regular Expressions Can Fail

Regular expressions can fail in the following ways:

* The Pattern Does Not Match Anything
* The Pattern Matches the Wrong Part of the Page
* The Pattern Matches Too Much

#####Example 01: Source
```<span class="product-link"> <a href="/books/almanac-0034.htm">World Almanac </a> </span>```

#####Example 01: Regex
```@<span class="product-link"> <a href="([\s\S]+?)">([\s\S]+?)</a>@```

In Example 01, we can see that our regular expression will match the source just fine. It will group the anchor's link and title in order. But would that same expression match any of the following?

#####Example 01: Problem Sources
```<span class="product-link featured"> <a href="/books/almanac-0034.htm">World Almanac </a> </span>```

```<span class="product-link"> <a href="/books/almanac-0034.htm">World Almanac</span>```

```<span class="product-link">  <a href="/books/almanac-0034.htm">World Almanac </a> </span>```

```<span class="product-link"><a href="/books/almanac-0034.htm">World Almanac </a> </span>```

None of these will be matched by the regular expression in Example 01.

In the first Problem Source above, the span has an additional class that we're not accounting for.
The second example is missing a crucial tag which would almost certainly cause an overflow issue since our pattern is using greedy selectors. (Can you imagine what would happen if there was another </a> later in the page?)
The final two are a little more tricky to spot: the former has an extra space between the span and a tags while the latter has no space. Neither of these are being matched.

Can you come up with a solution that will accurately match all of the examples here?

#####01: Solution
```
@<span[^>]+?class="product-link[^>]+?>\s*<a[^>]+?href="([^"]+)[^>]*>([^<]+)@i
```

This solution will match all of the previous examples without under- or over-shooting the data that we're after. It may look complicated at first, but we will step through each part to understand why this is a better solution.

#####01: Delimiters
```
@<span[^>]+?class="product-link[^>]+?>\s*<a[^>]+?href="([^"]+)[^>]*>([^<]+)@i
```

The first thing to notice is that our delimiters are @ symbols. Conventionally, when writing regular expressions, you would use / characters as delimiters, which has been the norm since Perl came about. However, we are parsing HTML pages where the / is used frequently and would have to be escaped if that's the delimiter we chose. The @, on the other hand, almost never appears in HTML, and is almost certainly not something you would need to match against when scraping a page. It is also highly visible, so we can easily see where a regular expression is when glancing at a piece of code.

Also note the i at the end of this expression. This is the case-insensitive modifier, which is often helpful in parsing pages. HTML tags and attribute names are not case-sensitive, and it's not unheard of for a site to start swapping or mixing casings on various parts of their site.

#####01: Tags
```@<span[^>]+?class="product-link[^>]+?>\s*<a[^>]+?href="([^"]+)[^>]*>([^<]+)@i```

With the exception of comments, HTML tags always open with a < which is immediately followed by an alphanumeric name. We can assume that starting our patterns the same way is safe when targeting tags, which we can see in the parts highlighted in green above.

We know that these tags have attributes, and we want to use that attribute data both for context and for parts of the data that we will return to the parser. Immediately after the start of the tag, we use a `[^>]+?`. This is our way of saying that, from the current point, match anything that is not a > until we hit what we want. All tags end with a >, so by matching anything that is not that character, we ensure that we will (usually) grab only what is inside of the current tag.

We use a + instead of a * because the first character after the tag's name must be whitespace. Beyond that, we're not sure if there are or will be any other attributes that we want to skip, which is why we don't just simply use `\s` in place of `[^>]`.

As always, the +? combination will make sure that the previous character or group is matched up until the next piece of the expression. In this example, we are looking for the attributes class and href in their respective tags.

In the span tag, we look for a span that has class of product-link. There could be other class names after this, which is why the class does not end with a ", though if another class were added, it could easily be on the left side of the product-link text. For example, if the span read `<span class="new product-link">`, our regex would break. We could anticipate this by writing
```class="[^">]*?product-link[^>]+?>```
instead of
```class="product-link[^>]+?>```

but there is little benefit in this as new classes seldom precede existing ones. It also makes it harder to read the regular expression when adding more checks to it.

#####01: Tag Attributes
```@<span[^>]+?class="product-link[^>]+?>\s*<a[^>]+?href="([^"]+)[^>]*>([^<]+)@i```

Here, we are grabbing the href of the anchor tag. Tag attributes appear with the attribute name on the left side of an = operator and their value on the right. Values may be enclosed in double quotes, single quotes, or may not have any surrounding delimiters at all. They also should not have any whitespace around the = sign. Though, in the vast majority of cases, the attribute values will be surrounded by double quotes, so we match what's between those.

Let's take a look at how we're grabbing this attribute:

```href="([^"]+)[^>]*>```

Here, we are matching an href followed by an = and the start of the attribute, which is the ". Next, we grab anything that is in between the pair of "" by using "([^"]+)". In other words, this is matching anything that is not another quote one or more times. We use + here instead of * because we generally only care about cases where there is data available. This expression would not match an anchor tag that had an href defined as href="", which is fine because that is useless to us.

#####01: Tag Name
```@<span[^>]+?class="product-link[^>]+?>\s*<a[^>]+?href="([^"]+)[^>]*>([^<]+)@i```

The final capture group in this example will grab the tag's name. Notice that we do not need a `<` at the end of this group, which would make it look like `([^<]+)<`. While that's generally not harmful, it's also useless because `[^<]+` already implies that our match will end once a `<` is found.

A common case where this would not match a name is where another tag is included between the end of the `<a>` and `</a>` in the page that we're scraping. Some sites do this to style their anchor text for different types of products or to denote a product's status. If a site like this is encountered, you can use the alternative method to grab through these extra tags:
```@<span[^>]+?class="product-link[^>]+?>\s*<a[^>]+?href="([^"]+)[^>]*>([\s\S]+?)</a>@i```

The above regular expression would match the following:
```<span class="product-link"><a href="/product-1234.php"><span class="out-of-stock">Fantastic Santa Hat</span></a>```

whereas our original regular expression would not.

####Grabbing Too Much

It is really easy to forget that regular expressions are all about pattern matching. It is easier still to forget that there are patterns that can be found within HTML through the structure of the tags, classes, and other characteristics of the elements in most cases. When this happens, regular expressions quickly turn from a sculptor's tool that extracts data with flexibility and precision into a wrecking ball that blows through its sources with concern for neither the data nor its context.

The most common enabler of this is the [\s\S]+? pattern (or [\s\S]*?). What this does is grabs any space or non-space between whatever surrounds it. When used within a well-defined context, it's a crucial tool. For example, when grabbing embedded JSON, it can't be beaten.

#####[Singer22 Product Code](https://padmin.trackif.com/domains/edit.php?domain=663&match=product&line=14&tab=parser)
```...if (preg_match('@\barrNotCachables\.setHistory\s*=\s*(\{[\s\S]+?\})\s*;@i', $content, $match) == 1) {
        $jsonData = json_decode($match[1], true);
     ...```

Here, we can see in the Singer22 Product code that we are looking for the JavaScript where `arrNotCachables.setHistory` is defining a JSON structure. We don't know what is in the structure, but we know that, since it's JSON, it has to start with a { and end with a }. Since it's JavaScript, it will also end with a ; since this is a simple declaration, so this is all going to ever only be one simple statement.

However, it's all too common to see examples like the following:


#####[Dick's Sporting Goods Product Code](https://padmin.trackif.com/domains/edit.php?domain=78&match=product&line=12&tab=parser)
```preg_match('@<h1[\s\S]+?>([\s\S]+?)<@', $content, $tmp);```

Here is a great example of a wrecking ball approach. The data that is being targeted exists after the first instance of an h1 that has at least one non-greater-than-sign beyond it. It won't match the following (though that can be intentional. It's just hard to tell for sure that this was the intent):

`<h1>Fantastic Santa Hat</h1>`

The data itself is between the opening tag and any other tag following it (which may not be the closing tag). A much cleaner way would be the following:

```if(preg_match('@<h1[^>]+>([^<]+)@i', $content, $match) == 1) { ...```

This code does the same thing. This version is not only shorter, though, but also conveys intent. Following the practices from earlier, we can see that the use of `[^>]+>` is meant to be a fast-forward to the end of an `<h1>` that has extra attribute data. After this, we have our capture group, `([^<]+)`. Again, this is our way of matching anything between two tags, and we don't need to end it with a `<` since that ending is already implied.