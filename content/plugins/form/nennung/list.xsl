<?xml version="1.0"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

	<xsl:output method="html"/>

	<xsl:param name="url"/>
	<xsl:param name="form"/>


	<!-- match all nodes -->
	<xsl:template match="/">

		<table class="form_list_table">

			<!-- add header -->
			<tr>
				<th class="form_list_head">ID</th>

				<xsl:for-each select="//fields/*">
					<th class="form_list_head">
						<xsl:copy-of select="name(.)"/>
					</th>
				</xsl:for-each>
			</tr>

			<xsl:apply-templates select="//data/*"/>
		</table>

	</xsl:template>



	<xsl:template match="data/*">

		<xsl:param name="pos" select="position()"/>
		<xsl:variable name="id" select="id"/>
		<xsl:variable name="data" select="."/>


		<tr>
			<td class="form_list_cell">
				<xsl:choose>

					<xsl:when test="$id != ''">
						<a>
							<xsl:attribute name="href">
								<xsl:text>?</xsl:text>
								<xsl:value-of select="$url"/>
								<xsl:text>&amp;action=detail</xsl:text>
								<xsl:text>&amp;id=</xsl:text>
								<xsl:value-of select="$id"/>
								<xsl:text>&amp;form=</xsl:text>
								<xsl:value-of select="$form"/>
							</xsl:attribute>

							<xsl:value-of select="$id"/>
						</a>
					</xsl:when>

					<xsl:otherwise>
						<xsl:value-of select="$id"/>
					</xsl:otherwise>

				</xsl:choose>
			</td>

			<xsl:for-each select="//fields/*">

				<xsl:variable name="n" select="name(.)"/>

				<td class="form_list_cell">
						<xsl:value-of select="$data/*[name() = $n]"/>
				</td>
			</xsl:for-each>
		</tr>
	</xsl:template>

</xsl:stylesheet>