<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:sitemap="http://www.sitemaps.org/schemas/sitemap/0.9">
<xsl:output method="html" encoding="UTF-8" indent="yes"/>

<xsl:template match="/">
<html>
<head>
    <title>XML Sitemap</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: #0a0a0a;
            color: #e0e0e0;
            padding: 40px 20px;
            line-height: 1.6;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
            color: #914bf1;
            font-weight: 700;
        }
        .intro {
            background: rgba(145, 75, 241, 0.1);
            border-left: 4px solid #914bf1;
            padding: 20px;
            margin: 30px 0;
            border-radius: 8px;
        }
        .intro h2 {
            color: #914bf1;
            font-size: 1.25rem;
            margin-bottom: 10px;
        }
        .intro p {
            color: #b0b0b0;
            line-height: 1.8;
        }
        .intro a {
            color: #914bf1;
            text-decoration: none;
            font-weight: 600;
        }
        .intro a:hover {
            text-decoration: underline;
        }
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin: 30px 0;
        }
        .stat-box {
            background: rgba(255, 255, 255, 0.05);
            padding: 20px;
            border-radius: 8px;
            border: 1px solid rgba(145, 75, 241, 0.3);
        }
        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: #914bf1;
        }
        .stat-label {
            color: #b0b0b0;
            font-size: 0.9rem;
            margin-top: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
            background: rgba(255, 255, 255, 0.03);
            border-radius: 8px;
            overflow: hidden;
        }
        thead {
            background: rgba(145, 75, 241, 0.2);
        }
        th {
            padding: 15px;
            text-align: left;
            font-weight: 600;
            color: #914bf1;
            border-bottom: 2px solid rgba(145, 75, 241, 0.5);
        }
        td {
            padding: 15px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        tr:hover {
            background: rgba(145, 75, 241, 0.1);
        }
        .url-link {
            color: #60a5fa;
            text-decoration: none;
            word-break: break-all;
        }
        .url-link:hover {
            text-decoration: underline;
            color: #914bf1;
        }
        .priority-high {
            color: #22c55e;
            font-weight: 600;
        }
        .priority-medium {
            color: #f59e0b;
            font-weight: 600;
        }
        .priority-low {
            color: #ef4444;
            font-weight: 600;
        }
        .changefreq {
            display: inline-block;
            padding: 4px 12px;
            background: rgba(145, 75, 241, 0.2);
            border-radius: 4px;
            font-size: 0.85rem;
            color: #914bf1;
        }
        .footer {
            margin-top: 50px;
            padding-top: 30px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            text-align: center;
            color: #666;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🗺️ XML Sitemap</h1>
        <p style="color: #b0b0b0; margin-bottom: 30px;">This sitemap helps search engines discover and index all pages on this website.</p>

        <div class="intro">
            <h2>📌 What is a Sitemap?</h2>
            <p>
                An XML sitemap is a file that lists all important pages of a website, making it easier for search engines like Google and Bing to crawl and index your content.
                This sitemap is automatically generated and updates whenever content is added or modified.
            </p>
            <p style="margin-top: 10px;">
                <strong>Submit this sitemap to:</strong><br/>
                • <a href="https://search.google.com/search-console" target="_blank">Google Search Console</a><br/>
                • <a href="https://www.bing.com/webmasters" target="_blank">Bing Webmaster Tools</a>
            </p>
        </div>

        <div class="stats">
            <div class="stat-box">
                <div class="stat-number"><xsl:value-of select="count(sitemap:urlset/sitemap:url)"/></div>
                <div class="stat-label">Total URLs</div>
            </div>
            <div class="stat-box">
                <div class="stat-number"><xsl:value-of select="count(sitemap:urlset/sitemap:url[sitemap:priority='1.0'])"/></div>
                <div class="stat-label">High Priority</div>
            </div>
            <div class="stat-box">
                <div class="stat-number"><xsl:value-of select="count(sitemap:urlset/sitemap:url[sitemap:priority='0.8'])"/></div>
                <div class="stat-label">Posts</div>
            </div>
            <div class="stat-box">
                <div class="stat-number"><xsl:value-of select="count(sitemap:urlset/sitemap:url[sitemap:priority='0.6'])"/></div>
                <div class="stat-label">Pages</div>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th style="width: 50%;">URL</th>
                    <th style="width: 20%;">Last Modified</th>
                    <th style="width: 15%;">Change Freq</th>
                    <th style="width: 15%;">Priority</th>
                </tr>
            </thead>
            <tbody>
                <xsl:for-each select="sitemap:urlset/sitemap:url">
                    <tr>
                        <td>
                            <a href="{sitemap:loc}" class="url-link" target="_blank">
                                <xsl:value-of select="sitemap:loc"/>
                            </a>
                        </td>
                        <td><xsl:value-of select="substring(sitemap:lastmod, 1, 10)"/></td>
                        <td>
                            <span class="changefreq">
                                <xsl:value-of select="sitemap:changefreq"/>
                            </span>
                        </td>
                        <td>
                            <xsl:choose>
                                <xsl:when test="sitemap:priority='1.0'">
                                    <span class="priority-high"><xsl:value-of select="sitemap:priority"/></span>
                                </xsl:when>
                                <xsl:when test="sitemap:priority='0.8'">
                                    <span class="priority-medium"><xsl:value-of select="sitemap:priority"/></span>
                                </xsl:when>
                                <xsl:otherwise>
                                    <span class="priority-low"><xsl:value-of select="sitemap:priority"/></span>
                                </xsl:otherwise>
                            </xsl:choose>
                        </td>
                    </tr>
                </xsl:for-each>
            </tbody>
        </table>

        <div class="footer">
            <p>Generated automatically • Updates when content changes</p>
            <p style="margin-top: 5px;">Built with ❤️ using AI-assisted development</p>
        </div>
    </div>
</body>
</html>
</xsl:template>

</xsl:stylesheet>
