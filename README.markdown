# rtBlogPlugin

## Security

Administration security (rtBlogPageAdmin) is **on** by default and requires the
permission `admin_blog`. This permission can be customized by setting in the
app.yml the `rt_wiki_admin_credential` property. This permission is added to the
`admin` group from the rtCorePlugin fixtures file.

Visitor security (rtBlogPage) is **off** by default. It can be enabled by setting
in the app.yml the `rt_blog_is_private` property to true. By default no specific
permission is configured. A permission can be configured by setting in the
app.yml the `rt_blog_visitor_credential` property.

