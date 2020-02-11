<?xml version="1.0"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

	<xsl:output method="xml"/>

	<xsl:param name="url"/>
	<xsl:param name="form"/>


	<!-- match all nodes -->
	<xsl:template match="/">

		<table class="form_list_table">
			<xsl:apply-templates select="//fields"/>
		</table>

	</xsl:template>



	<xsl:template match="fields">

		<xsl:param name="pos" select="position()"/>
		<xsl:variable name="id" select="../stat/id"/>

		<!-- add header -->
		<xsl:if test="$pos = 1">
			<tr>
				<xsl:for-each select="*">
					<th class="form_list_head"><xsl:value-of select="name(.)"/></th>
				</xsl:for-each>
			</tr>
		</xsl:if>


		<tr>
			<xsl:for-each select="*">
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

								<xsl:value-of select="text()"/>
							</a>
						</xsl:when>

						<xsl:otherwise>
							<xsl:value-of select="text()"/>
						</xsl:otherwise>

					</xsl:choose>
				</td>
			</xsl:for-each>
		</tr>
	</xsl:template>

</xsl:stylesheet>