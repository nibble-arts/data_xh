<?xml version="1.0"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

	<xsl:output method="xml"/>

	<xsl:param name="url"/>


	<!-- match all nodes -->
	<xsl:template match="/">

<xsl:copy-of select="."/>

<!-- 		<table class="form_list_table">
				<xsl:apply-templates select="//data"/>
		</table>
 -->
	</xsl:template>



	<xsl:template match="data">

		<xsl:param name="id" select="position()"/>

		<!-- add header -->
		<xsl:if test="$id = 1">
			<tr>
				<xsl:for-each select="*">
					<th class="form_list_head"><xsl:value-of select="name(.)"/></th>
				</xsl:for-each>
			</tr>
		</xsl:if>

		<tr>
			<xsl:for-each select="*">
				<td class="form_list_cell">
					<a>
						<xsl:attribute name="href">
							<xsl:text>?</xsl:text>
							<xsl:value-of select="$url"/>
							<xsl:text>&amp;id=</xsl:text>
							<xsl:value-of select="//meta[$id]/id"/>
						</xsl:attribute>

						<xsl:value-of select="text()"/>
					</a>
				</td>
			</xsl:for-each>
		</tr>
	</xsl:template>

</xsl:stylesheet>